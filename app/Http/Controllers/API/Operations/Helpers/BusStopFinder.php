<?php
namespace App\Http\Controllers\API\Operations\Helpers;


class BusStopFinder {
    private $apiKey; 

    public function __construct() {
        $this->apiKey = "2cdfb6f02cee478e8fd1ba294681cf9c";
    }

    public function getNearestBusStop($latitude, $longitude,$limit) {
        // API endpoint
        $apiEndpoint = 'https://api.geoapify.com/v2/places';

        // API parameters
        $categories = 'public_transport.bus';
        $bias = "proximity:$longitude,$latitude";
        $limit = $limit;

        // Build the API request URL
        $apiUrl = "$apiEndpoint?categories=$categories&bias=$bias&limit=$limit&apiKey=$this->apiKey";

        // Make the API request
        $response = file_get_contents($apiUrl);

        // Check if the request was successful
        if ($response === false) {
            // Handle error (you might want to log or throw an exception)
            return null;
        }

        // Parse the JSON response
        $data = json_decode($response, true);

        // Check if JSON decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Handle JSON decoding error
            return null;
        }


        // Check if the response contains any places
        if (isset($data['features'][0])) {
            // Extract the relevant information
            $properties = $data['features'][0]['properties'];
            $geometry = $data['features'][0]['geometry']['coordinates'];

            // Extracted information
            $coordinates = ['lon' => $geometry[0], 'lat' => $geometry[1]];
            $distance = $properties['distance'];
            $operator = $properties['datasource']['raw']['operator'];
            $street = $properties['street'];

            // Return the result
            return [
                'coordinates' => $coordinates,
                'distance' => $distance,
                'operator' => $operator,
                'street' => $street,
            ];

        } else {
            // No bus stops found
            return null;
        }
    }
}
?>