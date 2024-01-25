<?php
namespace App\Http\Controllers\API\Operations\Helpers;


class BusStopFinder {
    private $apiKey; 

    public function __construct() 
    {
        //GEOPIFY API KEY
        $this->apiKey = "";
    }

    public function getNearestBusStop($latitude, $longitude,$limit) {
        // // API endpoint
        // $apiEndpoint = 'https://api.geoapify.com/v2/places';

        // // API parameters
        // $categories = 'public_transport';
        // $bias = "proximity:$longitude,$latitude";
        // $limit = $limit;

        // // Build the API request URL
        // $apiUrl = "$apiEndpoint?categories=$categories&bias=$bias&limit=$limit&apiKey=$this->apiKey";

        // // Make the API request
        // $response = file_get_contents($apiUrl);

        // // Check if the request was successful
        // if ($response === false) {
        //     // Handle error (you might want to log or throw an exception)
        //     return null;
        // }

        // // Parse the JSON response
        // $data = json_decode($response, true);

        // // Check if JSON decoding was successful
        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     // Handle JSON decoding error
        //     return null;
        // }


        // // Check if the response contains any places
        // if (isset($data['features'][0])) {
        //     // Extract the relevant information
        //     $properties = $data['features'][0]['properties'];
        //     $geometry = $data['features'][0]['geometry']['coordinates'];


    

        //     $street ="Adresse inconnu";

        //     // Extracted information
        //     $coordinates = ['lon' => $geometry[0], 'lat' => $geometry[1]];
        //     $distance = $properties['distance'];
        //     // $operator = $properties['datasource']['raw']['operator'];
        //     if( isset($properties['street'])){
        //         $street = $properties['street'];

        //     }else if(isset($properties['road'])){
        //         $street = $properties['road'];

        //     }else{
        //         $street = $properties['suburb'];

        //     }

        //     // Return the result
        //     return [
        //         'coordinates' => $coordinates,
        //         'distance' => $distance,
        //         // 'operator' => $operator,
        //         'street' => $street,
        //     ];

        // } else {
        //     // No bus stops found
        //     return null;
        // }

   

        $coordinates = [
            "lon" => -17.450774799999998,
            "lat" => 14.682099399869736
        ];


                   return [
                'coordinates' => $coordinates,
                'distance' => 0,
                // 'operator' => $operator,
                'street' => "none",
            ];
    }




                                    //google maps api key
    function getStdreetName($point, $apiKey="") {
        $latitude = $point[0];
        $longitude = $point[1];
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$apiKey}";
    
        // Make a GET request to the Google Maps Geocoding API
        $response = file_get_contents($url);
    
        // Decode the JSON response
        $data = json_decode($response, true);
    
        if (isset($data["results"][1]["formatted_address"])) {
            return $data["results"][1]["formatted_address"];
        } elseif (isset($data["results"][2]["formatted_address"])) {
            // Fallback 1: Build the address from address components
            return $data["results"][2]["formatted_address"];

      
        }else if(isset($data["results"][3]["formatted_address"])){
            return $data["results"][3]["formatted_address"];

        }
    
        // Fallback 2: Use a generic message
        return "Lieu inconnu";
    }











    function getStreetName($point) {
        $latitude = $point[0];
        $longitude = $point[1];
        $url = "https://api.geoapify.com/v1/geocode/reverse?lat={$latitude}&lon={$longitude}&type=amenity&lang=fr&limit=1&format=json&apiKey={$this->apiKey}";
    
        // Make a GET request to the Geoapify API
        $response = file_get_contents($url);
    
        // Decode the JSON response
        $data = json_decode($response, true);
    
        if (isset($data['results'][0]['street'])) {
            return $data['results'][0]['street'];
        } else if(isset($data['results'][0]['address_line2'])) {
            // Return null if road information is not found
            return $data['results'][0]['address_line2'];
        }else{
            return "Lieu introuvable";
        }
    }
}





?>