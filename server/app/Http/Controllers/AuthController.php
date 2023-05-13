<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

//Controller for authenfication 
class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Validate data coming from user
        $fields = $request->validate([
            'name' => 'required | string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',
            'user_role' => 'required | string'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],
            'user_role' => $fields['user_role']
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function login(Request $request)
    {
        //Validate data coming from user
        $fields = $request->validate([
            'email' => 'required | string ',
            'password' => 'required | string '
        ]);

        //Check email
        $user = User::where('email', $fields['email'])->first();

        //check password
        if (!$user || $fields['password'] != $user->password) {
            return response([
                'message' => 'Bad request'
            ], 401);

        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }


    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}