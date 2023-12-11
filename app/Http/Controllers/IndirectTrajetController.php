<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;

use App\Models\IndirectTrajet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndirectTrajetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve and return a list of all IndirectTrajets
        $indirectTrajets = IndirectTrajet::all();
        return response()->json(['indirectTrajets' => $indirectTrajets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This might not be applicable for an API
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'depart' => 'required|string',
                'arrive' => 'required|string',
                'lignes' => 'required|json',
                'distance' => 'required|numeric',
            ]);
    
            // Create a new IndirectTrajet
            $indirectTrajet = IndirectTrajet::create($validatedData);
    
            // Return a JSON response with the created IndirectTrajet and a success message
            return response()->json(['message' => 'IndirectTrajet created successfully', 'indirectTrajet' => $indirectTrajet], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors(), 'data' => $request->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(IndirectTrajet $indirectTrajet)
    {
        // Return a JSON response with the specified IndirectTrajet
        return response()->json(['indirectTrajet' => $indirectTrajet]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IndirectTrajet $indirectTrajet)
    {
        // This might not be applicable for an API
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IndirectTrajet $indirectTrajet)
    {
        // Validate the request data
        $request->validate([
            'depart' => 'required|string',
            'arrive' => 'required|string',
            'lignes' => 'required|json',
            'distance' => 'required|numeric',
        ]);

        // Update the IndirectTrajet
        $indirectTrajet->update($request->all());

        // Return a JSON response with the updated IndirectTrajet
        return response()->json(['message' => 'IndirectTrajet updated successfully', 'indirectTrajet' => $indirectTrajet]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IndirectTrajet $indirectTrajet)
    {
        // Delete the IndirectTrajet
        $indirectTrajet->delete();

        // Return a JSON response with a success message
        return response()->json(['message' => 'IndirectTrajet deleted successfully']);
    }
}
