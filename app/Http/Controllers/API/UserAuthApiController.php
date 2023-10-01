<?php

namespace App\Http\Controllers\API;
use App\Mail\mailSender;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Symfony\Component\String\ByteString;

class UserAuthApiController extends Controller
{
    public function __construct()
    {
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication passed...
            $user = Auth::user();
            
            if($user->status==true)
            {
                Auth::logout();
                return response("Votre compte a été désactivée", 401);

            }
            $token = $user->createToken('auth-token')->accessToken;
    

            return response()->json(['token' => $token ,'username'=> $user->name ,'email'=> $user->email]);
            
        }else{
            return response("Email et/ou Mots de passe incorrecte", 401);
        }

       
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'Logged out']);
    }



    public function resetEmail(Request $request)
    {  
        $email = $request['email'];
    
        $user = User::where('email', $email)->first();
        
        if($user){
            $code = mt_rand(1000, 9999);
            $user->reset_code = $code;
            $user->save();
            Mail::to($email)->send(new mailSender($code));
        } else {
            return response("Ce compte n'existe pas !", 404);
        }
    
        return response("email envoyé !", 200);
    }

    public function reset(Request $request)
    {  
        $password = bcrypt($request['password']);
        $code = $request['code'];
    
        $user = User::where('reset_code',$code)->first();
        
        if($user){
            $user->reset_code ="";
            $user->password = $password;
            $user->save();
        } else {
            return response("Code   expiré ou incorrecte ! ", 401);
        }
    
        return response("mots de passe modifié  !", 201);
    }
    


    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with validation errors and a 422 status code (Unprocessable Entity)
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Attempt to create the user
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role'=> "Usager"
            ]);
    
            // // Fire the Registered event
            // event(new Registered($user));
    
            // Log in the user and generate an access token
            $user->role="Usager";
            $token = $user->createToken('auth-token')->accessToken;
    
            // Return a successful response with the token
            return response()->json(['token' => $token ,'username'=> $user->name ,'email'=> $user->email]);
        } catch (\Exception $e) {
            // Handle the exception and return an error response
            return response()->json(['error' => 'Registration failed. Please try again.'], 500);
        }
    }
    
    

    
}
