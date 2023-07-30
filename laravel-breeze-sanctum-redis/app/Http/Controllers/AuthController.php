<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register( Request $request){
        $data = $request->validate([
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($data);

        $expiresAt = now()->addHour();
        $token = $user->createToken('api-token',['*'],$expiresAt)->plainTextToken;

        return response()->json([
            'token' =>$token,
            'Type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Wrong credentials'
            ]);
        }

        $expiresAt = now()->addHour();
        $token = $user->createToken('api-token',['*'],$expiresAt)->plainTextToken;

        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'Type' => 'Bearer',
            'role' => $user->role
        ]);
    }

    public function logout(Request $request){
        // $request->user()->token()->delete();
        $request->user()->currentAccessToken()->delete();
       return [
        'message' => 'logged out'
       ];
    }
    
}
