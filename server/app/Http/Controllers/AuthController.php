<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Enseignant;
use App\Models\Administrateur;
use App\Models\Grade;
//Controller for authenfication 
class AuthController extends Controller
{
    
    public function register(Request $request)
    {  
        //pour creer un president dans la table user
         if(!Gate::allows('role_admin_univ')) {
        abort('403');
       }
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'type' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => $request->type
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e
            ], 500);
        }
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
        if (!$user || !Hash::check($fields['password'] , $user->password)) {
            return response([
                'message' => 'Bad request'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'items' => $user,
            
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