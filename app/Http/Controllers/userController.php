<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Admins= User::where('role','=','Administrateur')->get();
       return view('pages.administrateurs.administrateur',compact("Admins")) ;
    
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
    
        // Flash a success message to the session
        session()->flash('success', 'Compte créé avec succès!');
    
        return redirect()->route("admins.index");
    }



  public function reset(Request $request){
    $user = User::find($request['id']);
    $user->password=bcrypt("passer123");
    $user ->save();
    session()->flash("success","Mots de passe réinitialisé ! ");
    return redirect()->route("admins.index");
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
            toastr()->warning('Compte administrateur désactivée avec sucess ! ');

        }else{
            $user->status  = false;
            toastr()->success('Compte administrateur activée avec sucess ! ');

        }

        $user->update();

        return (redirect()->route("admins.index"));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    { 
        $user = User::find($id);
        $user->delete();
        toastr()->warning('Compte administrateur supprimé avec sucess ! ');
        return(redirect()->route('admins.index'));

    }
}
