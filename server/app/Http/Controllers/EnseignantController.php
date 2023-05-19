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
    

    //Create un enseignant
    public function store(Request $request){
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
    }
}


