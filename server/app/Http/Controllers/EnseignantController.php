<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
   
    //affichage des informations
    public function show($id)
    {
        $enseignant = Enseignant::findOrFail($id);
    
        return view('enseignants.show', compact('enseignant'));
    }
    
    // suppression d'un enseignant
    public function destroy($id)
    {
        $enseignant = Enseignant::find($id);

   
        // Supprimez l'enseignant de la table "enseignant"
        $enseignant->delete();

        // Supprimez également l'utilisateur associé de la table "users"
        $user = $enseignant->user;
     
        $user->delete();
        
        
        // Autres actions après la suppression réussie
    }
    
        // Redirection vers une autre page ou retour d'une réponse JSON si vous utilisez une API
    

    
    
    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $fields = $request->validate([
            'name' => 'required | string',
            'prenom'=>'required | string',
             'date_naissance'=>'required | date',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',
            'type' => 'required | string'

        ]);
        $etablissement = Etablissement::where('nom',$request->nom_etablissement)->first();
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
    
        // Récupérer l'enseignant à partir de l'ID
        $enseignant = Enseignant::findOrFail($id);
    
        // Mettre à jour les attributs de l'enseignant
        $enseignant->nom = $request->nom;
        $enseignant->prenom = $request->prenom;
        // Mettez à jour les autres attributs de l'enseignant que vous souhaitez modifier
    
        // Enregistrer les modifications de l'enseignant
        $enseignant->save();
    
        // Récupérer l'utilisateur associé à l'enseignant
        $user = User::findOrFail($enseignant->user_id);
    
        // Mettre à jour les attributs de l'utilisateur
        $user->name = $request->nom;
        // Mettez à jour les autres attributs de l'utilisateur que vous souhaitez modifier
    
        // Enregistrer les modifications de l'utilisateur
        $user->save();
    
        // Rediriger vers une autre page ou effectuer une action après la mise à jour réussie
    }
    
    
} 


