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
         
            
        ]);
         // Récupération de l'établissement "Ecole National des sciences appliquées"
         $etablissement = Etablissement::where('nom',$request->nom_etablissement)->first();
         
        
        if($type=='Enseignant'){
            $grade = Grade::where('nom',$request->designation)->first();
            $Enseignant = Enseignant::create([
                 // Récupération du grade "PA"
                 
                'name' => $fields['name'],
                'prenom' => $fields['prenom'],
                'ppr' => $fields['ppr'],
                'email' => $fields['email'],
                'date_naissance' => $fields['date_naissance'],
                'id_user' => $user->id,
                'id_etablissement'=> $etablissement->id,
                'id_grade'=>$grade->id,
            ]);
        
    }
    if($type=='Presidant'){
        $President = President::create([
            'name' => $fields['name'],
            'prenom' => $fields['prenom'],
            
            'email' => $fields['email'],
            'date_naissance' => $fields['date_naissance'],
            'id_user' => $user->id,
            
            
        ]);
    
}
if($type=='Administrateur_Etablissement'||$type=='directeur' ){
    $Admin_Etab = Administrateur_Etablissement::create([
        'name' => $fields['name'],
        'prenom' => $fields['prenom'],
        'ppr' => $fields['ppr'],
        'email' => $fields['email'],
        'date_naissance' => $fields['date_naissance'],
        'id_user' => $user->id,
        'id_etablissement'=> $etablissement->id,

        
    ]);
    $directeur =directeur::create([
        'name' => $fields['name'],
        'prenom' => $fields['prenom'],
        'ppr' => $fields['ppr'],
        'email' => $fields['email'],
        'date_naissance' => $fields['date_naissance'],
        'id_user' => $user->id,
        'id_etablissement'=> $etablissement->id,

        
    ]);

}

        

    

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