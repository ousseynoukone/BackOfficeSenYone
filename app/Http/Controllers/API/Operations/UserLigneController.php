<?php

namespace App\Http\Controllers\API\Operations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Ligne;
use App\Models\DTOS\LigneDto;

use function PHPUnit\Framework\matches;

class UserLigneController extends Controller
{
    /**
     * Display a listing of the resource , it get all the ligne files , then extract the itinéraire as coordinate and create a LigneDto , then add it to a array that is returned .
     */
    public function index()
    {   $arrayOfLignes = array();
        $lignes = Ligne::all();
        foreach ($lignes as $ligne) {
            $itineraireAsCoordinate = $this->extractItineraireAsCoordinate($ligne->itineraire);
        

            $checkPointsArray = explode("–", $ligne->check_point);
            // Remove any empty elements
            $checkPointsArray = array_filter($checkPointsArray);
            // Remove "\r" and "\n" from each element
            $checkPointsArray = array_map(function($point) {
                return str_replace(["\r", "\n"], '', $point);
            }, $checkPointsArray);


                    // Explode the "tarifs" string by "\r\n"
                    $tarifsArray = explode("\r\n", $ligne->tarifs);
                    // Remove any empty elements
        $tarifsArray = array_filter($tarifsArray);
                    // Remove  "\n" from each element

        $tarifsArray = array_map(function($tarif) {
            return str_replace(["\n"], '', $tarif);
        }, $tarifsArray);


        

        
            $ligneDto = new LigneDto($ligne->id,$ligne->numero,$checkPointsArray,$itineraireAsCoordinate,$tarifsArray);
            array_push($arrayOfLignes,$ligneDto);
        }
        return  response($arrayOfLignes,200);

    }






    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

//      public function show(int $idLigne)
//      {  

//         $ligne = Ligne::find($idLigne);
 
//         $itineraireAsCoordinate = $this->extractItineraireAsCoordinate($ligne->itineraire);
        
//         $ligneDto = new LigneDto($ligne->id,$ligne->numero,$ligne->check_point,$itineraireAsCoordinate,$ligne->tarifs);

//   return response($ligneDto,200);
 
//  } 


 ///Sert juste a extraire les coordinné d'une ligne sous forme de matrice  avec chaque sous tableau une latitude , longitude et altitude
    

    public function extractItineraireAsCoordinate(String $cheminDuFichier)
    {  

             
$content1 =  Storage::get($cheminDuFichier);
$content1 = str_replace("\n", '', $content1);
$content1 = str_replace("\t", '', $content1);

// Define a regular expression pattern to match the <LineString> element
$pattern = '/<LineString>.*?<\/LineString>/s';

// Use preg_match to find the first match in the content
if (preg_match($pattern, $content1, $matches)) {
    // $matches[0] now contains the <LineString> element and its contents
    $lineString = $matches[0];

    // You can then work with the $lineString as needed
    // For example, to remove any remaining line breaks and tabs:
    $lineString = str_replace(["\n", "\t"], '', $lineString);

    // Now $lineString contains the cleaned <LineString> element
}
$content = $lineString;

// Extract coordinates from KML data
preg_match_all('/<coordinates>(.*?)<\/coordinates>/s', $content, $matches);
$coordinates = $matches[1][0];
// Split the coordinates into individual pairs
$coordinatePairs = explode(' ', $coordinates);
$coordinatePairs = array_filter($coordinatePairs);
$tabCoordonate = [];

foreach ($coordinatePairs as $index=> $pair) {
    $coordinates = explode(',', $pair);
    $lon = (float)$coordinates[1];
    $lat = (float)$coordinates[0];
    $tabCoordonate[] = [$lon, $lat];
}


 return response( $tabCoordonate,200);

}  



public function extractItineraireAsCoordinateNoResponse(String $cheminDuFichier)
{  

         
$content1 =  Storage::get($cheminDuFichier);
$content1 = str_replace("\n", '', $content1);
$content1 = str_replace("\t", '', $content1);

// Define a regular expression pattern to match the <LineString> element
$pattern = '/<LineString>.*?<\/LineString>/s';

// Use preg_match to find the first match in the content
if (preg_match($pattern, $content1, $matches)) {
// $matches[0] now contains the <LineString> element and its contents
$lineString = $matches[0];

// You can then work with the $lineString as needed
// For example, to remove any remaining line breaks and tabs:
$lineString = str_replace(["\n", "\t"], '', $lineString);

// Now $lineString contains the cleaned <LineString> element
}
$content = $lineString;

// Extract coordinates from KML data
preg_match_all('/<coordinates>(.*?)<\/coordinates>/s', $content, $matches);
$coordinates = $matches[1][0];
// Split the coordinates into individual pairs
$coordinatePairs = explode(' ', $coordinates);
$coordinatePairs = array_filter($coordinatePairs);
$tabCoordonate = [];

foreach ($coordinatePairs as $index=> $pair) {
$coordinates = explode(',', $pair);
$lon = (float)$coordinates[1];
$lat = (float)$coordinates[0];
$tabCoordonate[] = [$lon, $lat];
}


return $tabCoordonate;

}  

    /**
     * Show the form for editing the specified resource.
     */

     public function show(string $id)
     {
         
     }
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
