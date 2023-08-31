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
            "numero" => "required|numeric|min:1",
            "check_point" => "required|string"
        ]);
        // Handle file upload
        $uploadedFile = $request->file('itineraire');
        $kmlFilePath = $uploadedFile->store('kml_files');
        
    
        // Create a new Ligne instance
        $ligne = new Ligne([
            'itineraire' => $kmlFilePath,
            'numero' => $validated['numero'],
            'check_point' => $validated['check_point'],
        ]);
    
        // Save the Ligne instance to the database
        $ligne->save();
    
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
        $content= Storage::get($ligne->itineraire);


        $initialMarkers = [
            [
                'position' => [
                    'lat' => 28.625485,
                    'lng' => 79.821091
                ],
                'draggable' => true
            ],
            [
                'position' => [
                    'lat' => 28.625293,
                    'lng' => 79.817926
                ],
                'draggable' => false
            ],
            [
                'position' => [
                    'lat' => 28.625182,
                    'lng' => 79.81464
                ],
                'draggable' => true
            ]
        ];
        return view('pages.lignes.ligne-show', compact('ligne','initialMarkers','content'));
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
            "check_point" => "required|string"
        ]);

        $ligne->update($validated);

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

        return back()->with('message', 'item deleted successfully');
    }
}
