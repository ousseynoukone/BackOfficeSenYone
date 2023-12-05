<?php

namespace App\Http\Controllers\API\Operations;
use App\Models\Ligne;
use App\Models\DTOS\LigneDto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Operations\UserLigneController;

use function PHPUnit\Framework\isEmpty;
use function Ramsey\Uuid\v1;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\API\Operations\Helpers\BusStopFinder;
use Point;

class UserTrajetController extends Controller
{

    private $userLigneController;

    public function __construct()
    {
        // Instantiate UserLigneController when UserTrajetController is created
        $this->userLigneController = new UserLigneController();
    }
    /**
     * Display a listing of the resource.
     * 
     */
    public function makeTrajet(Request $request)
    {
        // Validation rules
        $rules = [ 
            'departLatitude' => 'required|numeric',
            'departLongitude' => 'required|numeric',
            'arriveLatitude' => 'required|numeric',
            'arriveLongitude' => 'required|numeric',
            'approximation' => 'required|integer', // Adjust the rule based on your needs
        ];
    
        // Custom validation messages
        $messages = [
            'required' => 'The :attribute field is required.',
            'numeric' => 'The :attribute must be a number.',
            'integer' => 'The :attribute must be an integer.',
        ];
    
        // Validate the request data
        try {
            $validatedData = $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            // Validation failed, return the error response
            return response()->json(['error' => $e->errors()], 400);
        }
    
        // If validation passes, retrieve the validated data
        $departLatitude = $validatedData['departLatitude'];
        $departLongitude = $validatedData['departLongitude'];
        $destinationLatitude = $validatedData['arriveLatitude'];
        $destinationLongitude = $validatedData['arriveLongitude'];
        $approximation = $validatedData['approximation'];
       
        $trajet= $this->searchForLine($departLatitude, $departLongitude,$approximation, $destinationLatitude, $destinationLongitude);
 

        if(isset( $trajet["IndirectLines"][0])){
            $indirectLines = $trajet["IndirectLines"][0];
            if(!empty($indirectLines)){
                $trajet["IndirectLines"][0] =  $this->filterUndirectLineToGetTheExacteRouteWithoutAnyUselessPoint($indirectLines) ;

            }
        }

        //get distance direct traject and min
        $directLines = $trajet["DirectLines"];
   if(!empty($directLines)){
        $minDistance = PHP_INT_MAX;
        $minDistanceLineIndex = -1;

        foreach ($directLines as $index => $directLine) {
            $lineCoordinates = $directLine[0][1];

            $extractedPoints =  $this->extractPointBeetwen($lineCoordinates,$directLine["StartingPoint"],$directLine["EndingPoint"]);

            $distance = $this->calculateLineLength($extractedPoints);

            // Add the distance to the directLine
            $trajet["DirectLines"][$index]["distance"] = $distance;

            // Update minDistance and minDistanceLineIndex if a shorter distance is found
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $minDistanceLineIndex = $index;
            }
        }

        // Mark the line with the minimum distance as "min"
        if ($minDistanceLineIndex !== -1) {
            $trajet["DirectLines"][$minDistanceLineIndex]["status"] = true;
        }
    }

        if(!empty($trajet["IndirectLines"])){
            $result = $this->findDistanceOfIndirectTraject($trajet["IndirectLines"][0]);
            $trajet["IndirectLines"]["distance"] =     $result;


        }






    
      return   $trajet;
    }

    function findDistanceOfIndirectTraject($undirectLines) {
        $distance = 0 ;

        foreach ($undirectLines as $key => $undirectLine) {
            
            $extractedPoints =  $this->extractPointBeetwen($undirectLine["ligne"][1],$undirectLine["StartingPoint"],$undirectLine["EndingPoint"]);

            $distance += $this->calculateLineLength($extractedPoints);
        }

        return $distance;

        
    }

    



 












public function filterUndirectLineToGetTheExacteRouteWithoutAnyUselessPoint($indirectLines) {
   

    $ultimateStartingPoint = $indirectLines[0]["StartingPoint"];

    $ultimateEndingPoint =[];



    if(count($indirectLines)==3){
        $ultimateEndingPoint = $indirectLines[2]["EndingPoint"];

    }
    if(count($indirectLines)==2){
        $ultimateEndingPoint = $indirectLines[1]["EndingPoint"];

    }




        
        $line1 = $indirectLines[0][0];
    
        if (count($indirectLines) == 3) {
            $line2 = $indirectLines[1][0];
            $line3 = $indirectLines[2][0][0];
        }
    
        if (count($indirectLines) == 2) {
            $line3 = [];
            $line2 = $indirectLines[1][0][0];
        }
    
        //---------------------------------
        $nearestPoint1 = [];
        $nearestPoint2 = [];
    
        if (count($indirectLines) == 3) {
            $nearestPoint1 = $this->findNearestPointOnNextLine($line1, $line2);
            $nearestPoint2 = $this->findNearestPointOnNextLine($line2, $line3);
        } else {
            $nearestPoint1 = $this->findNearestPointOnNextLine($line1, $line2);
        }


        //----------------------------------



        $indirectTrajets = [];

        if( !empty($line1)){

            $indirectTrajets [] = [
                "StartingPoint" => $ultimateStartingPoint,
                "EndingPoint" => $nearestPoint1,
                "ArretbusD"=>$this->getNearestBusStop( $ultimateStartingPoint,1),
                "ArretbusA"=>$this->getNearestBusStop($nearestPoint1,1),
                "ligne"=>$line1
            ];

        }

        if( !empty($line2)){
            $endingPoint =  $nearestPoint2;

                if(count($indirectLines)==2){
        $endingPoint = $indirectLines[1]["EndingPoint"];

    }

            //le point leplus proche de nearestPoint1 par rapport au ligne suivant

            $departPoint = $this->findClosestPointInLine($line2,$nearestPoint1);
         
       
            $indirectTrajets [] = [
                "StartingPoint" => $departPoint,
                "EndingPoint" =>  $endingPoint,
                "ArretbusD"=>$this->getNearestBusStop($departPoint,1),
                "ArretbusA"=>$this->getNearestBusStop($endingPoint,1),
                "ligne"=>$line2
            ];

        }

        if( !empty($line3)){

            $departPoint = $this->findClosestPointInLine($line3,$nearestPoint2);


            $indirectTrajets [] = [
                "StartingPoint" =>  $departPoint,
                "EndingPoint" => $ultimateEndingPoint,
                "ArretbusD"=>$this->getNearestBusStop( $departPoint,1),
                "ArretbusA"=>$this->getNearestBusStop($ultimateEndingPoint,1),
                "ligne"=>$line3
            ];

        }




    return $indirectTrajets;
    
    }
    


    public function findNearestPointOnNextLine($line, $nextLine) {
        $minDistance = PHP_INT_MAX;
        $nearestPoint = null;
    
        foreach ($line[1] as $point) {
            foreach ($nextLine[1] as $nextPoint) {
                $distance = $this->haversineDistance($point[0], $point[1], $nextPoint[0], $nextPoint[1]);
    
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $nearestPoint = $point;
                }
            }
        }
    
        return $nearestPoint;
    }
    


    
    










    public function searchForLine($departLatitude,$departLongitude,$approximation,$destinationLatitude,$destinationLongitude)
    {
        $arrayOfLignes = array();
        $lignes = Ligne::all();
        foreach ($lignes as $ligne) {
            $itineraireAsCoordinate = $this->userLigneController->extractItineraireAsCoordinateNoResponse($ligne->itineraire);
            $arrayOfLineWithCoordinate[0] = $ligne->numero;
            $arrayOfLineWithCoordinate[1] = $itineraireAsCoordinate;
            array_push($arrayOfLignes, $arrayOfLineWithCoordinate);
        }
    
        $nearestLine = $this->NearestLineFromUserLocation($departLatitude,$departLongitude,$approximation,$arrayOfLignes,$destinationLatitude,$destinationLongitude);
    
        // $nearestLine now contains the information about the nearest bus line that reaches the destination
        // You can return or use this information as needed
        return ($nearestLine);
    }



    

    function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $R = 6371; // Radius of the Earth in kilometers
    
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
    
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $R * $c; // Distance in kilometers
    
        return $distance;
    }


    function NearestLineFromUserLocation($userLatitude, $userLongitude, $maxDistance, $arrayOfLignes, $destinationLatitude, $destinationLongitude)
    {
        $undirectLines = [
            "IndirectLines" => [],
        ];
    
        $directLines = [
            "DirectLines" => [],
        ];
    
        $addedLines = [];

        $minDistancePoint = null;
        $breakAll = false;
   
        foreach ($arrayOfLignes as $line) {
      
            $lineId = $line[0];
    
            // Check if the line has already been added
            if (in_array($lineId, $addedLines)) {
                continue;
            }

            $nearestPointFromTheUserLocation = PHP_INT_MAX;

    
            foreach ($line[1] as $point) {
        
                $distanceToUser = $this->haversineDistance($userLatitude, $userLongitude, $point[0], $point[1]);

                if($nearestPointFromTheUserLocation> $distanceToUser && $distanceToUser < $maxDistance){
                    $nearestPointFromTheUserLocation = $point;
                }
      
                if ($distanceToUser < $maxDistance) {


                    $isCloseToDestination = $this->userIsCloseToDestination($line[1], $destinationLatitude, $destinationLongitude);
    
                    if (!empty($isCloseToDestination)) {
                        // Check if the line has already been added
                        if (!in_array($lineId, $addedLines)) {




                            $directLines["DirectLines"][] = ["StartingPoint"=>$nearestPointFromTheUserLocation,$line,"EndingPoint"=>$isCloseToDestination,"busStopD"=>$this->getNearestBusStop($nearestPointFromTheUserLocation,1),"busStopA"=>$this->getNearestBusStop($isCloseToDestination,1)];
                            $addedLines[] = $lineId;
                        }
                    } else {
                    $undirectLine [] = [$line,"StartingPoint"=>$nearestPointFromTheUserLocation,"busStop"=>$this->getNearestBusStop($nearestPointFromTheUserLocation,1)];

              
                    for ($i = 0; $i < 3; $i++) {
                       
                        $currentLineToActualLineAndDestination = $this->findNearestLine($i,$line, $arrayOfLignes, $destinationLatitude, $destinationLongitude);
                        

                   
                  
                        $point = $this->userIsCloseToDestination($currentLineToActualLineAndDestination[0][1], $destinationLatitude, $destinationLongitude);
                       
                        if (empty($point)==false) {
                        //   break if 3 attemps has passed or we have reaches or destination
                     
                           $breakAll = true;
                           $undirectLine[] = [$currentLineToActualLineAndDestination,"EndingPoint"=>$point];
                            break;
                        }
                        if(empty($point)==true && $i == 2 ){
                            $breakAll = true;
                            $undirectLine=[];
                            break;


                        }



                        // Set the current line to the result of the previous call
                        $undirectLine[] = $currentLineToActualLineAndDestination;

                        $line = $currentLineToActualLineAndDestination;
                    }








                                        // Check for lines starting nearly at the same point in $undirectLine
                    // for ($i = 0; $i < count($undirectLine) - 1; $i++) {
                    
                    //     for ($j = $i + 1; $j < count($undirectLine)-1; $j++) {
              
                    //             $distanceBetweenStartPoints = $this->haversineDistance(
                    //                 $undirectLine[$i][0][1][0][0], $undirectLine[$i][0][1][0][1],
                    //                 $undirectLine[$j][0][1][0][0], $undirectLine[$j][0][1][0][1],
                    //             );
                           
                    

                    //         // Assuming 100m is the threshold for considering lines starting nearly at the same point
                    //         $thresholdDistance = 0.1; // Adjust this value as needed

                    //         if ($distanceBetweenStartPoints < $thresholdDistance) {
                    //             // Remove one of the lines (choose which one to keep or remove based on your criteria)
                    //             // For example, you can remove the line with the longer length
                    //             $lengthI = $this->calculateLineLength($undirectLine[$i][0][1]);
                    //             $lengthJ = $this->calculateLineLength($undirectLine[$j][0][1]);

                    //             if ($lengthI > $lengthJ) {
                    //                 unset($undirectLine[$j]);
                    //             } else {
                    //                 unset($undirectLine[$i]);
                    //             }
                    //         }
                    //     }
                    // }

                                        // // If undirectLine contains 1 or 4 lines, reset it to an empty array
                                        if (count($undirectLine) > 4 || count($undirectLine) < 2  ) {
                                            $undirectLine = [];
                                        }
                    

                    // Reset array keys after removal
                    $undirectLine = array_values($undirectLine);

                    // Continue with the rest of your code
                    // ...




                    // Check if the undirect line is already in the array

                    // if (!empty($undirectLines["IndirectLines"])) {
                    //     $undirectLineExists = false;
                    // foreach ($undirectLines["IndirectLines"] as $existingLine) {
                    //     if ($existingLine[0] === $undirectLine[0]) {
                    //         $undirectLineExists = true;
                    //         break;
                    //     }
                    // }

                    // // Add the undirect line if it doesn't exist in the array
                    // if (!$undirectLineExists) {
                    //     $undirectLines["IndirectLines"][] = $undirectLine;
                    // }
                    // }
                    $undirectLines["IndirectLines"][] = $undirectLine;

                    if($breakAll==true ){
                        break;
        
                    }
                }

                
                }
            }
            if($breakAll==true ){
                break;

            }
        }
    
        return array_merge($directLines, $undirectLines);
    }



    function calculateLineLength($lineCoordinates)
{
        $lineLength = 0;

        // Iterate through each point in the line
        for ($i = 1; $i < count($lineCoordinates); $i++) {
            $point1 = $lineCoordinates[$i - 1];
            $point2 = $lineCoordinates[$i];

            // Calculate the distance between two consecutive points and add to the total length
            $distance = $this->haversineDistance($point1[0], $point1[1], $point2[0], $point2[1]);
            $lineLength += $distance;
        }

        return $lineLength;
}

    
    
function findNearestLine($i,$currentLine, $arrayOfLignes, $destinationLatitude, $destinationLongitude)
{
    $nearestLineToCurrent = null;
    $nearestLineToDestination = null;
    $minDistanceToCurrent = PHP_INT_MAX;
    $minDistanceToDestination = PHP_INT_MAX;
    $nearestPointByCurrentLine = null; // Updated to store the nearest point


    foreach ($arrayOfLignes as $line) {
      
        if($i>=1){
  
        if ($line[0] != $currentLine[0][0]) { // Skip the current line
            // Initialize distances for each line
            $minDistanceToCurrentLine = PHP_INT_MAX;
            $minDistanceToDestinationLine = PHP_INT_MAX;
            $nearestPointToCurrentLine = null; // Updated to store the nearest point

            // Find the nearest point on the current line to any point on the other line
            foreach ($line[1] as $point) {
                // j'ai mis une condition ici car la strcuture de currentLine change une fois la fonction est appélé pour la premiere fois a cause des ajout au niveau des returns (point ect...)
                if($i>=1){
                    foreach ($currentLine[0][1] as $currentPoint) {
                        $distanceToCurrentLine = $this->haversineDistance($currentPoint[0], $currentPoint[1], $point[0], $point[1]);
                        if ($distanceToCurrentLine < $minDistanceToCurrentLine) {
                            $minDistanceToCurrentLine = $distanceToCurrentLine;
                            $nearestPointToCurrentLine = $point;
                        }
                    }
    
                }else{

                foreach ($currentLine[1] as $currentPoint) {
                    $distanceToCurrentLine = $this->haversineDistance($currentPoint[0], $currentPoint[1], $point[0], $point[1]);
                    if ($distanceToCurrentLine < $minDistanceToCurrentLine) {
                        $minDistanceToCurrentLine = $distanceToCurrentLine;
                        $nearestPointToCurrentLine = $point;
                    }
                }
            }



            }

            // Check if this line is closer to the current line
            if ($minDistanceToCurrentLine < $minDistanceToCurrent) {
                $minDistanceToCurrent = $minDistanceToCurrentLine;
                $nearestLineToCurrent = $line;
                $nearestPointByCurrentLine = $nearestPointToCurrentLine;
            }
        }
    }else{
        if ($line[0] != $currentLine[0]) { // Skip the current line
            // Initialize distances for each line
            $minDistanceToCurrentLine = PHP_INT_MAX;
            $minDistanceToDestinationLine = PHP_INT_MAX;
            $nearestPointToCurrentLine = null; // Updated to store the nearest point

            // Find the nearest point on the current line to any point on the other line
            foreach ($line[1] as $point) {
                // j'ai mis une condition ici car la strcuture de currentLine change une fois la fonction est appélé pour la premiere fois a cause des ajout au niveau des returns (point ect...)
                if($i>=1){
                    foreach ($currentLine[0][1] as $currentPoint) {
                        $distanceToCurrentLine = $this->haversineDistance($currentPoint[0], $currentPoint[1], $point[0], $point[1]);
                        if ($distanceToCurrentLine < $minDistanceToCurrentLine) {
                            $minDistanceToCurrentLine = $distanceToCurrentLine;
                            $nearestPointToCurrentLine = $point;
                        }
                    }
    
                }else{

                foreach ($currentLine[1] as $currentPoint) {
                    $distanceToCurrentLine = $this->haversineDistance($currentPoint[0], $currentPoint[1], $point[0], $point[1]);
                    if ($distanceToCurrentLine < $minDistanceToCurrentLine) {
                        $minDistanceToCurrentLine = $distanceToCurrentLine;
                        $nearestPointToCurrentLine = $point;
                    }
                }
            }



            }

            // Check if this line is closer to the current line
            if ($minDistanceToCurrentLine < $minDistanceToCurrent) {
                $minDistanceToCurrent = $minDistanceToCurrentLine;
                $nearestLineToCurrent = $line;
                $nearestPointByCurrentLine = $nearestPointToCurrentLine;
            }
        }
    }


    }

    // if($i>=1){
    //     echo("currentLinev i ".$currentLine[0][0]."\n");

    // }else{
    //     echo("currentLinev ".$currentLine[0]."\n");

    // }
    // echo("nearestLineToCurrent ".$nearestLineToCurrent[0]."\n");

    // Now, find the nearest point to the destination on the chosen line
    foreach ($nearestLineToCurrent[1] as $point) {
        $distanceToDestinationLine = $this->haversineDistance($point[0], $point[1], $destinationLatitude, $destinationLongitude);
        if ($distanceToDestinationLine < $minDistanceToDestination) {
            $minDistanceToDestination = $distanceToDestinationLine;
            $nearestLineToDestination = $nearestLineToCurrent;
        }
    }

    // echo("distanceToDestinationLine ".$distanceToDestinationLine."\n");
    // echo("minDistanceToDestination ".$minDistanceToDestination."\n");


    //echo($currentLine[0]);
    // Include the nearest point on the chosen line in the result
    return [$nearestLineToDestination,  'StartingPoint' => $nearestPointByCurrentLine,"busStop"=>$this->getNearestBusStop($nearestPointByCurrentLine,1) ];
}

    
public function findClosestPointInLine($line, $targetPoint)
{
    $minDistance = PHP_INT_MAX;
    $closestPoint = null;

    foreach ($line[1] as $point) {
        $distance = $this->haversineDistance($point[0], $point[1], $targetPoint[0], $targetPoint[1]);

        if ($distance < $minDistance) {
            $minDistance = $distance;
            $closestPoint = $point;
        }
    }

    return $closestPoint;
}





    function userIsCloseToDestination($lineCoordinates, $destinationLatitude, $destinationLongitude) {
        $thresholdDistance = 1; // Adjust this value as needed
    
        foreach ($lineCoordinates as $point) {
            $distanceToDestination = $this->haversineDistance($point[0], $point[1], $destinationLatitude, $destinationLongitude);
            if ($distanceToDestination < $thresholdDistance) {
                return $point;
            }
        }
    
        return [];
    }




    public function getNearestBusStop($point,$limit)
    {
        if(!empty($point)){

      

        $latitude = $point[0];
        $longitude = $point[1];
       $busStopFinder = new BusStopFinder();
       $result = $busStopFinder->getNearestBusStop($latitude, $longitude,$limit);

       // Check if a bus stop was found
       if ($result !== null) {
           return $result;
       }else{
                   return null ;

       }
         }
       
        else {
           return null ;
       }


    }

    public function extractPointBeetwen($line,$pointA,$pointB){
                        // Extract points between $nearestPointFromTheUserLocation and $isCloseToDestination
                $startExtraction = false;
                $extractedPoints = [];
                foreach ($line as $pointOnLine) {
                    if ($pointOnLine === $pointA) {
                        $startExtraction = true;
                    }
                    if ($startExtraction) {
                        $extractedPoints[] = $pointOnLine;
                        if ($pointOnLine === $pointB) {
                            break; // Stop extraction when reaching $isCloseToDestination
                        }
                    }
                }

                return $extractedPoints;

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
