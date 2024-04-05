<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Fonction de création de compte/inscription
    public function signup(Request $request)
    {
        $validDatas = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$validDatas) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 400);
        }

        $thisUserExist = User::where('email', $validDatas["email"])->first();

        if ($thisUserExist) {
            return response()->json([
                'message' => 'Cet utilisateur est déjà inscrit.'
            ], 400);
        }

        $user = new User();

        $user->name = $validDatas['name'];
        $user->email = $validDatas['email'];
        $user->password = $validDatas['password'];

        $user->save();


        return response()->json([
            'message' => 'Compte créé avec succès.',
        ], 201);

        
    }

    //  Fonction de connexion
    public function login(Request $request)
    {
        $validatedCredantials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (!$validatedCredantials) {
            return response()->json([
                "message" => "Invalid Credentials."
            ], 422);
        }

        $existingUser = User::where('email', $validatedCredantials['email'])->first();
        if (!$existingUser) {
            return response()->json([
                "message" => "Identifiants incorrects."
            ], 404);
        }


        $validUser = Auth::attempt($validatedCredantials);

        if (!$validUser) {
            return response()->json([
                "message" => "Identifiants incorrects.",
            ], 422);
        }


        $user = Auth::user();

        if ($user) {
            $token = $user->createToken("main")->plainTextToken;
        }


        return response()->json([
            "user" => $user,
            "token" => $token,
            "message" => "Connected successfully.",
        ], 200);
    }

    public function logout (Request $request){
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            "message" => "Loged out successfully."
        ], 200);
    }

    public function update(Request $request)
    {
        $validCredentials = $request->validate([
            "name" => "required|string",
            "email" => 'required|email',
        ]);

        $userId = auth()->id();
        $user = User::where('id', $userId)->first();
        if(!$user){
            return response()->json([
                "message"=>"This User doesn't exist."
            ], 404);
        }

        $user->name = $validCredentials["name"];
        $user->email = $validCredentials["email"];

        $user->save();

        return response()->json([
            "message"=> "Profile modifié avec succès.",
            "user" => $user          
        ], 200);
    }

    public function get (){
        $user = Auth::user();
        return response()->json([
           "message" => "Success.",
           "user" => $user,
        ],200);
    }
}


