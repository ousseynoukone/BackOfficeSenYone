<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\Ligne;
use Illuminate\Http\Request;

class LigneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lignes = Ligne::all();

        return view('pages.lignes.ligne', compact('lignes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lignes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $this->validate($request, [
            "itineraire" => "required|max:2048", 
            "numero" => "required|unique:lignes,numero|numeric|min:1",
            "check_point" => "required|string",
            "tarifs" => "required|string"
        ]);
        // Handle file upload
        $uploadedFile = $request->file('itineraire');
        $kmlFilePath = $uploadedFile->store('kml_files');
        
    
        // Create a new Ligne instance
        $ligne = new Ligne([
            'itineraire' => $kmlFilePath,
            'numero' => $validated['numero'],
            'check_point' => $validated['check_point'],
            'tarifs' => $validated['tarifs'],
        ]);
    
        // Save the Ligne instance to the database
        $ligne->save();
        session()->flash('success', 'Enrégistrement de la ligne effectué avec sucess');

        return back()->with('message', 'Item stored successfully');
    }
    
  
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ligne $ligne
     * @return \Illuminate\Http\Response
     */


    public function show(Ligne $ligne)
    {  

             
        $content1 =  Storage::get($ligne->itineraire);
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
$tabStartAndEndCordinate = [];
// Split the coordinates into individual pairs
$coordinatePairs = explode(' ', $coordinates);
$coordinatePairs = array_filter($coordinatePairs);
// Create a GeoJSON feature for the LineString
$feature = [
    'type' => 'Feature',
    'properties' => ['Name' => 'Ligne 1 AFTU'],
    'geometry' => [
        'type' => 'LineString',
        'coordinates' => [],
    ],
];
// dd($coordinatePairs);
// Iterate through the coordinate pairs and convert them to GeoJSON format
foreach ($coordinatePairs as $index=> $pair) {
    $coordinates = explode(',', $pair);
    $lon = (float)$coordinates[0];
    $lat = (float)$coordinates[1];
    if($index==0){
        $tabStartAndEndCordinate[0] =$lat; 
        $tabStartAndEndCordinate[1] =$lon; 
    }
    
    if($index==count($coordinatePairs)-1){
        $tabStartAndEndCordinate[2] =$lat; 
        $tabStartAndEndCordinate[3] =$lon; 
    }
    $feature['geometry']['coordinates'][] = [$lon, $lat];
  

}



// Create a GeoJSON object
$geojson = [
    'type' => 'FeatureCollection',
    'name' => 'Ligne 1 AFTU',
    'crs' => [
        'type' => 'name',
        'properties' => ['name' => 'urn:ogc:def:crs:OGC:1.3:CRS84'],
    ],
    'features' => [$feature],
];

// Convert the GeoJSON to JSON and output it
$jsonGeoJSON = json_encode($geojson, JSON_PRETTY_PRINT);
$content=$jsonGeoJSON;
        // dd( $lineString);

    // Remove \t and \n characters from the KML content
    // dd ( stripcslashes( $content2));

        return view('pages.lignes.ligne-show', compact('ligne','content','tabStartAndEndCordinate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ligne $ligne
     * @return \Illuminate\Http\Response
     */
    public function edit(Ligne $ligne)
    {
        return view('pages.lignes.ligne-edit', compact('ligne'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Ligne $ligne
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ligne $ligne)
    {
        $validated = $this->validate($request, [
            "numero" => "required|numeric | min:1",
            "check_point" => "required|string",
            "tarifs" => "required|string"
        ]);


        $ligne->update($validated);
        toastr()->success('Mise a jour effectué avec sucess');

        return back()->with('message', 'item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ligne $ligne
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ligne $ligne)
    {
        $ligne->delete();
        
        // toastr()->warning('Suppression effectué avec sucess');
        session()->flash('warning', 'Suppression effectué avec sucess');

        return back()->with('message', 'item deleted successfully');
    }



    public function getFile($filename)
{
    // Assuming $filename is the name of the stored file
    $filePath = 'kml_files/' . $filename;

    // Check if the file exists
    if (Storage::exists($filePath)) {
        // Get the file's content
        $fileContent = Storage::get($filePath);

        // Define the response headers
        $headers = [
            'Content-Type' => 'application/octet-stream', // Adjust the content type as needed
            'Content-Disposition' => 'inline; filename="' . $filename . '"', // Optionally, set the desired filename
        ];

        // Return the file as a response
        return response($fileContent, 200, $headers);
    }

    // File not found, return a 404 response or handle the error as needed
    return response('File not found', 404);
}
}
