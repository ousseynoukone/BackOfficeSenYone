<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class usagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Usagers= User::where('role','=','Usager')->get();
        return view('pages.administrateurs.usager',compact("Usagers")) ;  
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
        $user = User::find($id);
    
        if($user->status==false)
        {
            $user->status  = true;
            toastr()->warning('Compte usager désactivée avec sucess ! ');

        }else{
            $user->status  = false;
            toastr()->success('Compte usager activée avec sucess ! ');

        }

        $user->update();

        return (redirect()->route("usagers.index"));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
