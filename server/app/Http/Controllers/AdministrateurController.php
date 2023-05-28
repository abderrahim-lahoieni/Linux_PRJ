<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Grade;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditEtablissementRequest;

class EnseignantController extends Controller
{

    //affichage des informations
    public function show($id)
    {
        $Admin = Administrateur::findOrFail($id);
        return response()->json([
            'status_code' => 200,
            'items' => $Admin
        ]);

    }
    public function Profile()
    {

        $Admincode = Auth::id();

        $Admin = Administrateur::where('id_user', $Admincode)->first();
        $id_Admin = $Admin->id;
        $Administrateur = Administrateur::findOrFail($id_Admin);
        return response()->json([
            'status_code' => 200,
            'items' => $Administrateur,
        ]);

    }
    public function AffichageAll_President()
    {
        $enseignant = Administrateur::all();
        return response()->json([
            'status_code' => 200,
            'items' => $enseignant
        ]);

    }
    public function AffichagebyEtablissement_President($id_etablissement)
    {
        $administrateur = Administrateur::where('Etablissement', $id_etablissement)->first();
        return response()->json([
            'status_code' => 200,
            'items' => $administrateur
        ]);

    }


    // Suppression d'un Administrateur
    public function destroy($id)
    {
        $admin = Administrateur::findOrFail($id);

        $admin->delete();

        return response()->json([
            'message' => 'L\'administrateur a été supprimé avec succès'
        ]);
    }

    public function store_Administrateur_Etablissement(Request $request)
    {


        //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',
            'nom_etablissement' => 'required |string',
            'ville' => 'required |`string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',

        ]);



        $user = User::create([

            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'type' => 'Administrateur_Etablissement'
        ]);
        $Etablissement = Etablissement::where('nom', $fields['nom_etablissement'])->where('ville', $fields['ville'])->first();
        $id_etablissement = $Etablissement->id;
        $Administrateur = Administrateur::create([
            'nom' => $fields['nom'],
            'prenom' => $fields['prenom'],
            'ppr' => $fields['ppr'],


            'Etablissement' => $id_etablissement,

            'id_user' => $user['id'],
        ]);

        return response()->json([
            'status_code' => 200,
            'items' => $Administrateur,
        ]);
    }



    //Create un Administrateur
    public function store_Directeur(Request $request)
    {

        $Admin = Auth::id();

        // Récupérer l'Administrateur en utilisant le code de connexion
        $Administrateur = Administrateur::where('id_user', $Admin)->first();
        $id_etablissement = $Administrateur->id_etablissement;
        //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',


            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',

        ]);



        $user = User::create([

            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'type' => 'Directeur'
        ]);

        $Administrateur = Administrateur::create([
            'nom' => $fields['nom'],
            'prenom' => $fields['prenom'],
            'ppr' => $fields['ppr'],


            'etablissement' => $id_etablissement,

            'id_user' => $user['id'],
        ]);

        return response()->json([
            'status_code' => 200,
            'items' => $Administrateur,
        ]);
    }
    public function update_profile(Request $request)
    {
        $admin = Auth::id();
        $Administrateur = Administrateur::where('id', $admin)->first();

        $fields = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ppr' => 'required|string',

        ]);


        $Administrateur->nom = $fields['nom'];
        $Administrateur->prenom = $fields['prenom'];
        $Administrateur->ppr = $fields['ppr'];


        $Administrateur->save();

        return response()->json(['message' => 'Votre INformations sont  mises à jour avec succès'], 200);
    }
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ppr' => 'required|string',

        ]);

        $Administrateur = Administrateur::findOrFail($id);
        $Administrateur->nom = $fields['nom'];
        $Administrateur->prenom = $fields['prenom'];
        $Administrateur->ppr = $fields['ppr'];


        $Administrateur->save();

        return response()->json(['message' => 'Informations personnelles de l\'Administrateur sont mises à jour avec succès'], 200);
    }

    public function changer_etablissement(Request $request, $id)
    {
        $fields = $request->validate([

            'nom' => 'required | string',
            'ville' => 'required | string',
        ]);
        $etablissement = Etablissement::where('nom', $fields['nom'])->where('ville', $fields['ville'])->first();
        $Administrateur = Administrateur::findOrFail($id);
        $Administrateur->etablissement = $etablissement->id;

        $Administrateur->save();

        return response()->json(['message' => 'Etablissement changé'], 200);
    }

}