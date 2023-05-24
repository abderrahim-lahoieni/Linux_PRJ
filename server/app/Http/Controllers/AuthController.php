<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Enseignant;
use App\Models\Administrateur;
use App\Models\Grade;
//Controller for authenfication 
class AuthController extends Controller
{
    
     /* public function register_Administrateur(Request $request)
    {
   //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',
            'nom_etablissement' => 'required | string',
            'type' => 'required | string',
        ]);
        $user = User::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'type' => $request['type'],
        ]);
        if( $request['type']=='Adiministrateur_Etblissement'||$request['type']=='directeur' ){
            $etablissement = Etablissement::where('nom', $request['nom_etablissement'])->first();
            $id = $etablissement->id;
            
        $Administrateur =Administrateur::create([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',
            
            'date_naissance'=>'required | string',
            'telephone' => 'required | integer',
            'id_etablissement'=>$id,
           
        
            'id_user'=>$user['id'],
        ]);} 
    

 


        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }*/
    
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