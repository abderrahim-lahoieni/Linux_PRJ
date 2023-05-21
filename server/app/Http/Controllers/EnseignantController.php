<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use App\Models\Grade;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
   
    //affichage des informations
    public function show($id)
    {
        $enseignant = Enseignant::findOrFail($id);
        return response()->json([
            'status_code' => 201 ,
            'items' => $enseignant
        ]);
        
    }
    
    // Suppression d'un enseignant
    public function destroy($id)
    {
        $enseignant = Enseignant::find($id);
        // Supprimez l'enseignant de la table "enseignant"
        $enseignant->delete();
        
        // Supprimez également l'utilisateur associé de la table "users"
        //Trigger 
        $user = $enseignant->user_id;
        $user1 = User::where('id', $user)->first();
        $user1->delete();
        
        return response()->json([
            'status_code' => 201 ,
            'success' => 'L\'enseignant est supprimé avec succès'
        ]);
        
        
    }
    
       
    

    //Create un enseignant
    public function store(Request $request)
    {
        //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',
            
            'date_naissance'=>'required | string',
            'telephone' => 'required | string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',
            'nom_etablissement' => 'required | string',
            'designation'=>'required | string',
           
        ]);
        $user = User::create([
            'name' => $fields['nom'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'type' => 'Enseignant'
        ]);
        $etablissement = Etablissement::where('nom', $request['nom_etablissement'])->first();
        $id = $etablissement->id;
        $grade = Grade::where('designation', $request['designation'])->first();
        $id_grade = $grade->id;
        $Enseignant = Enseignant::create([
            'nom' => $fields['nom'],
            'prenom' => $fields['prenom'],
            'ppr' => $fields['ppr'],
            
            'date_naissance'=>$fields['date_naissance'],
            'telephone' => $fields['telephone'],
            'etablissement_id'=>$id,
            'grade_id'=>$id_grade,
            'user_id'=>$user->id,
        ]);
        return response()->json([
            'items' => $Enseignant
        ]);

   /*  public function store(Request $request){
        
        $fields = $request->validate([
            'ppr' => 'required | string',
            'nom' => 'required | string ',
            'prenom' => 'required | string ',
            'date_naissance' => 'required | date',
            'etablissement_id' => 'required ',
            'grade_id' => 'required ',
            'user_id' => 'required '
        ]);
        $enseignant = Enseignant::create([
            'ppr' => $fields['ppr'],
            'nom' => $fields['nom'],
            'prenom' => $fields['password'],
            'date_naissance' => $fields['date_naissance'],
            'etablissement_id' => $fields['etablissement_id'],
            'grade_id' => $fields['grade_id'],
            'user_id' => $fields['user_id']
        ]);
        return $enseignant;
    } */
}
}

