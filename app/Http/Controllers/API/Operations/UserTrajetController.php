<?php

namespace App\Http\Controllers\API\Operations;
use App\Models\Ligne;
use App\Models\DTOS\LigneDto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Operations\UserLigneController;
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
        $destinationLatitude = 14.685590; // Destination latitude;,  
        $destinationLongitude = -17.454571; // Destination longitude;


        // 14.759197, -17.436353

        $nearestLine = $this->NearestLineFromUserLocation(14.759197,-17.436353,1,$arrayOfLignes,$destinationLatitude,$destinationLongitude);
    
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
    
        foreach ($arrayOfLignes as $line) {
            $lineId = $line[0];
    
            // Check if the line has already been added
            if (in_array($lineId, $addedLines)) {
                continue;
            }
    
            foreach ($line[1] as $point) {
                $distanceToUser = $this->haversineDistance($userLatitude, $userLongitude, $point[0], $point[1]);
    
                if ($distanceToUser < $maxDistance) {
                    $isCloseToDestination = $this->userIsCloseToDestination($line[1], $destinationLatitude, $destinationLongitude);
    
                    if ($isCloseToDestination) {
                        // Check if the line has already been added
                        if (!in_array($lineId, $addedLines)) {
                            $directLines["DirectLines"][] = $line;
                            $addedLines[] = $lineId;
                        }
                    } else {
                        // Find the nearest line to the destination among the lines closest to the current line
                        $undirectLine = $this->findNearestLine($line, $arrayOfLignes, $destinationLatitude, $destinationLongitude);
                        
                        $undirectLineId = $undirectLine[0];
    
                        // Check if the undirect line has already been added
                        if (!in_array($undirectLineId, $addedLines)) {
                            $undirectLines["IndirectLines"][] = $undirectLine;
                            $addedLines[] = $undirectLineId;
                        }
                    }
                }
            }
        }
    
        return array_merge($directLines, $undirectLines);
    }
    
    

    function findNearestLine($currentLine, $arrayOfLignes, $destinationLatitude, $destinationLongitude)
    {
        $nearestLine = null;
        $minDistanceToDestination = PHP_INT_MAX;
    
        foreach ($arrayOfLignes as $line) {
            if ($line !== $currentLine) { // Skip the current line
                $minDistance = PHP_INT_MAX;
    
                foreach ($line[1] as $point) {
                    foreach ($currentLine[1] as $currentPoint) {
                        $distance = $this->haversineDistance($currentPoint[0], $currentPoint[1], $point[0], $point[1]);
    
                        if ($distance < $minDistance) {
                            $minDistance = $distance;
                        }
                    }
                }
    
                // Calculate the distance between the last point of the line and the destination
                $lastPoint = end($line[1]);
                $distanceToDestination = $this->haversineDistance($lastPoint[0], $lastPoint[1], $destinationLatitude, $destinationLongitude);
    
                if ($distanceToDestination < $minDistanceToDestination) {
                    $minDistanceToDestination = $distanceToDestination;
                    $nearestLine = $line;
                }
            }
        }
    
        return $nearestLine;
    }
    




    function userIsCloseToDestination($lineCoordinates, $destinationLatitude, $destinationLongitude) {
        $thresholdDistance = 1; // Adjust this value as needed
    
        foreach ($lineCoordinates as $point) {
            $distanceToDestination = $this->haversineDistance($point[0], $point[1], $destinationLatitude, $destinationLongitude);
            if ($distanceToDestination < $thresholdDistance) {
                return true;
            }
        }
    
        return false;
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
