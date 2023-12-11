<?php

namespace App\Http\Controllers\API\Operations\Helpers;

class DistanceFinder
{
    private $api_key;

    public function __construct()
    {
        $this->api_key = "2cdfb6f02cee478e8fd1ba294681cf9c";
    }

    public function getRoute($origin, $destination, $mode = 'walk',$distanceOnly=false)
    {
        
        $url = "https://api.geoapify.com/v1/routing?waypoints={$origin[0]},{$origin[1]}|{$destination[0]},{$destination[1]}&mode={$mode}&apiKey={$this->api_key}";

        $response = file_get_contents($url);

        if ($response === false) {
            // Handle error, e.g., unable to fetch the data
            return ['error' => 'Unable to fetch route data'];
        }

        $data = json_decode($response, true);

        if (!$data || !isset($data['features'][0]['properties'])) {
            // Handle invalid or unexpected response
            return ['error' => 'Invalid or unexpected response'];
        }

        $routeProperties = $data['features'][0]['properties'];

        // Extract and return only the required information
        if($distanceOnly==false){
            $result = [
                'distance' => $routeProperties['distance'],
                'duration' => $this->convertSecondsToMinutes($routeProperties['time']),
                'coordinates' => $this->extractCoordinates($data),
            ];
        }else{
            $result = $routeProperties['distance'] / 1000;;
        
        }


       return $result;
    }

    private function extractCoordinates($data)
    {
        $coordinates = $data['features'][0]['geometry']['coordinates'][0];
    
        // Invert the order of latitude and longitude for each coordinate pair
        $invertedCoordinates = array_map(function ($coordinate) {
            return [$coordinate[1], $coordinate[0]];
        }, $coordinates);
    
        return $invertedCoordinates;
    }
    

    private function convertSecondsToMinutes($seconds)
    {
        return round($seconds / 60, 2);
    }
}
?>
