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
    

    
    
        use App\Models\Enseignant;

        public function update(Request $request, $id)
        {
            // Valider les données de la requête
             $request->validate([
                'name' => 'required | string',
                'prenom' => 'required | string ',
                'telephone' => 'required | string ',
                'ppr' => 'required | string',
                'date_naissance' => 'required | date',
            ]);
        
            // Récupérer l'enseignant à partir de l'ID
            $enseignant = Enseignant::findOrFail($id);
        
            // Mettre à jour les attributs de l'enseignant
            $enseignant->nom = $request->nom;
            $enseignant->prenom = $request->prenom;
            $enseignant->ppr = $request->nom;
            $enseignant->telephone= $request->telephone;
            $enseignant->date_naissance= $request->date_naissance;
                   
            // Enregistrer les modifications de l'enseignant
            $enseignant->save();
            
            // Rediriger vers une autre page ou effectuer une action après la mise à jour réussie
        }
        
    
    
} 


