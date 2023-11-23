<?php

namespace App\Http\Controllers\API\Operations;
use App\Models\Ligne;
use App\Models\DTOS\LigneDto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Operations\UserLigneController;

use function PHPUnit\Framework\isEmpty;

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
    public function index()
    {
        $arrayOfLignes = array();
        $lignes = Ligne::all();
        foreach ($lignes as $ligne) {
            $itineraireAsCoordinate = $this->userLigneController->extractItineraireAsCoordinateNoResponse($ligne->itineraire);
            $arrayOfLineWithCoordinate[0] = $ligne->numero;
            $arrayOfLineWithCoordinate[1] = $itineraireAsCoordinate;
            array_push($arrayOfLignes, $arrayOfLineWithCoordinate);
        }
    
        // Destination coordinates
        $destinationLatitude =14.683141; // Destination latitude;,  
        $destinationLongitude = -17.452698; // Destination longitude;


        // 14.759197, -17.436353

        $nearestLine = $this->NearestLineFromUserLocation(14.771785,-17.416451,1,$arrayOfLignes,$destinationLatitude,$destinationLongitude);
    
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
                            $directLines["DirectLines"][] = ["StartingPoint"=>$nearestPointFromTheUserLocation,$line,"EndingPoint"=>$isCloseToDestination];
                            $addedLines[] = $lineId;
                        }
                    } else {
                    $undirectLine [] = ["StartingPoint"=>$nearestPointFromTheUserLocation,$line];

              
                    for ($i = 0; $i < 3; $i++) {
                        $currentLineToActualLineAndDestination = $this->findNearestLine($line, $arrayOfLignes, $destinationLatitude, $destinationLongitude);
                        // var_dump($currentLineToActualLineAndDestination[0][1]);
                        $point = $this->userIsCloseToDestination($currentLineToActualLineAndDestination[0][1], $destinationLatitude, $destinationLongitude);
                        if (!empty($point) || $i === 2) {
                        //   break if 3 attemps has passed or we have reaches or destination
                           $breakAll = true;
                           $undirectLine[] = [$currentLineToActualLineAndDestination,"EndingPoint"=>$point];
                            break;
                        }



                        // Set the current line to the result of the previous call
                        $undirectLine[] = $currentLineToActualLineAndDestination;

                        $line = $currentLineToActualLineAndDestination;
                    }








                                        // Check for lines starting nearly at the same point in $undirectLine
                    for ($i = 0; $i < count($undirectLine) - 1; $i++) {
                    
                        for ($j = $i + 1; $j < count($undirectLine)-1; $j++) {
                            // Compare the starting points of lines $i and $j
                            $distanceBetweenStartPoints = $this->haversineDistance(
                                $undirectLine[$i][1][0][0], $undirectLine[$i][1][0][1],
                                $undirectLine[$j][1][0][0], $undirectLine[$j][1][0][1]
                            );


                            // Assuming 100m is the threshold for considering lines starting nearly at the same point
                            $thresholdDistance = 0.1; // Adjust this value as needed

                            if ($distanceBetweenStartPoints < $thresholdDistance) {
                                // Remove one of the lines (choose which one to keep or remove based on your criteria)
                                // For example, you can remove the line with the longer length
                                $lengthI = $this->calculateLineLength($undirectLine[$i][1]);
                                $lengthJ = $this->calculateLineLength($undirectLine[$j][1]);

                                if ($lengthI > $lengthJ) {
                                    unset($undirectLine[$j]);
                                } else {
                                    unset($undirectLine[$i]);
                                }
                            }
                        }
                    }

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

    
    
function findNearestLine($currentLine, $arrayOfLignes, $destinationLatitude, $destinationLongitude)
{
    $nearestLineToCurrent = null;
    $nearestLineToDestination = null;
    $minDistanceToCurrent = PHP_INT_MAX;
    $minDistanceToDestination = PHP_INT_MAX;
    $nearestPointByCurrentLine = null; // Updated to store the nearest point

    foreach ($arrayOfLignes as $line) {
        if ($line !== $currentLine) { // Skip the current line
            // Initialize distances for each line
            $minDistanceToCurrentLine = PHP_INT_MAX;
            $minDistanceToDestinationLine = PHP_INT_MAX;
            $nearestPointToCurrentLine = null; // Updated to store the nearest point

            // Find the nearest point on the current line to any point on the other line
            foreach ($line[1] as $point) {
                foreach ($currentLine[1] as $currentPoint) {
                    $distanceToCurrentLine = $this->haversineDistance($currentPoint[0], $currentPoint[1], $point[0], $point[1]);
                    if ($distanceToCurrentLine < $minDistanceToCurrentLine) {
                        $minDistanceToCurrentLine = $distanceToCurrentLine;
                        $nearestPointToCurrentLine = $point;
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

    // Now, find the nearest point to the destination on the chosen line
    foreach ($nearestLineToCurrent[1] as $point) {
        $distanceToDestinationLine = $this->haversineDistance($point[0], $point[1], $destinationLatitude, $destinationLongitude);
        if ($distanceToDestinationLine < $minDistanceToDestination) {
            $minDistanceToDestination = $distanceToDestinationLine;
            $nearestLineToDestination = $nearestLineToCurrent;
        }
    }

    // Include the nearest point on the chosen line in the result
    return [  'StartingPoint' => $nearestPointByCurrentLine , $nearestLineToDestination];
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
