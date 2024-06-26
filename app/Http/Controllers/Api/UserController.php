<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoguserRequest;
use App\Http\Requests\RegisterUser;


class UserController extends Controller
{
//========================================================================================

public function register(RegisterUser $request)
{
    try {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role ?? 'user'; 
         $user->password = $request->password;

        $user->save();

      $token = $user->createToken('AuthToken')->accessToken;

        return response()->json([
            'status' => 200,
            'msg' => 'User created successfully',
            'token' => $token, 
            'user' => $user,
            'date_added' => $user->created_at->format('Y-m-d H:i:s'),
            'id' => $user->id
        ]);

    } catch (Exception $e) {
        return response()->json(['error' => 'User creation failed'], 500);
    }
}

//========================================================================================

public function login(LoguserRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('Private_BackendToken')->plainTextToken;

            return response()->json([
                'status' => 200,
                'msg' => 'Connexion réussie',
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'msg' => 'Mot de passe incorrect',
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 200,
            'msg' => 'Déconnexion réussie',
        ]);
    }
    
    
}
