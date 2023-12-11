<?php

namespace App\Http\Controllers;

use App\Models\DirectTrajet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class DirectTrajetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $directTrajets = DirectTrajet::all();
        return response()->json(['directTrajets' => $directTrajets]);
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
     {
         try {
             $validatedData = $request->validate([
                 'depart' => 'required|string',
                 'arrive' => 'required|string',
                 'departLat' => 'required|numeric', // assuming double values are accepted as numeric
                 'departLon' => 'required|numeric',
                 'arriveLat' => 'required|numeric',
                 'arriveLon' => 'required|numeric',
                 'distance' => 'required|numeric',
                 'frequence' => 'required|integer',
                 'ligne_id' => 'required|exists:lignes,id',
                ]);
     
             $directTrajet = DirectTrajet::create($validatedData);
     
             return response()->json(['message' => 'DirectTrajet created successfully', 'directTrajet' => $directTrajet], Response::HTTP_CREATED);
         } catch (ValidationException $e) {
             return response()->json(['message' => 'Validation failed', 'errors' => $e->errors(), 'data' => $request->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
         }
     }

    /**
     * Display the specified resource.
     */
    public function show(DirectTrajet $directTrajet)
    {
        return response()->json(['directTrajet' => $directTrajet]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DirectTrajet $directTrajet)
    {
        $request->validate([
            'depart' => 'required|string',
            'arrive' => 'required|string',
            'departPoint' => 'required|json',
            'arrivePoint' => 'required|json',
            'distance' => 'required|numeric',
            'frequence' => 'required|integer',
            'ligne_id' => 'required|exists:lignes,id',
        ]);

        $directTrajet->update($request->all());

        return response()->json(['message' => 'DirectTrajet updated successfully', 'directTrajet' => $directTrajet]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DirectTrajet $directTrajet)
    {
        $directTrajet->delete();

        return response()->json(['message' => 'DirectTrajet deleted successfully']);
    }
}
