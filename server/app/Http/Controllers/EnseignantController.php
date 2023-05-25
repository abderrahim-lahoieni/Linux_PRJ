<?php

namespace App\Http\Controllers;

use App\Models\Administrateur;
use App\Models\Grade;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditEtablissementRequest;
use Exception;

class EnseignantController extends Controller
{

    //affichage des informations
    public function show($id)
    {
        $enseignant = Enseignant::findOrFail($id);
        return response()->json([
            'status_code' => 200,
            'items' => $enseignant
        ]);

    }

    public function Profile()
    {
       
        $professorcode =Auth::id();

        $professeur = Enseignant::where('user_id', $professorcode)->first();
        $id_professeur = $professeur->id;
        $enseignant = Enseignant::findOrFail( $id_professeur);
        return response()->json([
            'status_code' => 200 ,
            'items' => $enseignant
        ]);
   
   }

    public function AffichageAll_President()
    {
        $enseignant = Enseignant::all();
        return response()->json([
            'status_code' => 200,
            'items' => $enseignant
        ]);

    }
    public function AffichagebyEtablissement_President($id_etablissement)
    {
        $enseignant = Enseignant::where('etablissement_id', $id_etablissement)->get();
        return response()->json([
            'status_code' => 200,
            'items' => $enseignant
        ]);

    }
    
    //Pour Directeur et administrateur etablissement
    public function Affichage_Administrateur()
    {
        $Admin = Auth::id();
        $Administrateur = Administrateur::where('user_id', $Admin)->first();
        $id_etablissement = $Administrateur->etablissement_id;
        $enseignant = Enseignant::where('etablissement_id', $id_etablissement)->get();
        return response()->json([
            'status_code' => 200,
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
            'status_code' => 201,
            'success' => 'L\'enseignant est supprimé avec succès'
        ]);


    }




    //Create un enseignant
    public function store(Request $request)
    {

        $Admin = Auth::id();

        // Récupérer le professeur en utilisant le code de connexion
        $Administrateur = Administrateur::where('user_id', $Admin)->first();
        $id_etablissement = $Administrateur->etablissement_id;
        //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',

            'date_naissance' => 'required | string',
            'telephone' => 'required | string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',
            'designation' => 'required | string',
            'etat' => 'required'
        ]);
        $user = User::create([
            'name' => $fields['nom'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'type' => 'Enseignant'
        ]);

        $grade = Grade::where('designation', $request['designation'])->first();
        $id_grade = $grade->id;
        $Enseignant = Enseignant::create([
            'nom' => $fields['nom'],
            'prenom' => $fields['prenom'],
            'ppr' => $fields['ppr'],

            'date_naissance' => $fields['date_naissance'],
            'telephone' => $fields['telephone'],
            'etablissement_id' => $id_etablissement,
            'grade_id' => $id_grade,
            'user_id' => $user['id'],
            'etat' => $fields['etat']
        ]);

        return response()->json([
            'items' => $Enseignant
        ]);
    }
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ppr' => 'required|string',
            'date_naissance' => 'required|string',
            'telephone' => 'required|string',
        ]);

        $enseignant = Enseignant::findOrFail($id);
        $enseignant->nom = $fields['nom'];
        $enseignant->prenom = $fields['prenom'];
        $enseignant->ppr = $fields['ppr'];
        $enseignant->date_naissance = $fields['date_naissance'];
        $enseignant->telephone = $fields['telephone'];

        $enseignant->save();

        return response()->json(['message' => 'Informations personnelles de l\'enseignant mises à jour avec succès'], 200);
    }

}