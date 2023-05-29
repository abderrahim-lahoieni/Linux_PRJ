<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Exception;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Enseignant;
use App\Models\Administrateur;
use App\Models\Grade;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetNotification;
//Controller for authenfication 
class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    'type' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
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
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad request'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'items' => $user,
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

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = ($query = User::query());

        $user = $user->where($query->qualifyColumn('email'), $request->input('email'))->first();

        //if no such user exists then throw an error 
        if (!$user || !$user->email) {
            return response()->error('No Record Found', 'Incorrect Email Address Provided', 404);
        }

        //Generate a 4 digit random Token 
        $resetPasswordToken = str_pad(random_int(1, 9999), 4, '0', STR_PAD_RIGHT);

        //in case User has already requested for forgot password don't create another record
        //Instead Update the existing token with the new token 
        if (!$userPassReset = PasswordReset::where('email', $user->email)->first()) {
            //Store Token in DB with Token Expiration Time i.e: 1 hour 
            PasswordReset::create([
                'email' => $user->email ,
                'token' => $resetPasswordToken
            ]);
        }else{
            //Store Token in DB with Token Expiration Time i.e: 1 hour
            $userPassReset->update([
                'email' => $user->email ,
                'token' => $resetPasswordToken
            ]);
        }

        //Send Notification to the user about the reset token 
        $user->notify(
            new PasswordResetNotification(
                $user,
                $resetPasswordToken
            )
        );
    }

    public function reset(ResetPasswordRequest $request){

        //validate the request 
        $attributes = $request->validated();

        $user = User::where('email',$attributes['email'])->first();

        //Throw exception if user is not found
        if(!$user)
        {
            return response()->error('No Record Found','Incorrect Email Address Provided',404);
        }

        $resetRequest = PasswordReset::where('email',$user->email)->first();

        if(!$resetRequest || $resetRequest->token != $request->token){
            return response()->error('An Error Occured Please try Again','Token Mistach',400);
        }

        //update User's Password 

        $user->fill([
            'password' => Hash::make($attributes['password'])
        ]);

        $user->save();

        //Dalete previous all Tokens 
        $user->tokens()->delete();

        $resetRequest->delete();

        //Get Token for Authenticated User 
        $token = $user->createToken('authToken')->plainTextToken;

        //Create a Response
        return response()->json([
            'success' => 'Password Reset Success',
            'user' => $user,
            'token' => $token  
        ]);
    }
}
