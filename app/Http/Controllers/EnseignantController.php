<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Grade;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use App\Models\Administrateur;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EditEtablissementRequest;

class EnseignantController extends Controller
{

    public function index()
    {
        if (Gate::allows('role_admin_univ', Auth::user())) {

            $enseignant = Enseignant::all();
            
            return response()->json([
                'status_code' => 200,
                'items' => $enseignant
            ]);
        }

    }


    //affichage des informations
    public function show($id)
    {
        if (Gate::any(['role_admin_eta', 'role_directeur', 'role_president'], Auth::user())) {


            $enseignant = Enseignant::findOrFail($id);
            return response()->json([
                'status_code' => 200,
                'items' => $enseignant
            ]);

        }
    }

    public function Profile()
    {

        if (Gate::allows('role_enseignant', Auth::user())) {

            $professorcode = Auth::id();

            $professeur = Enseignant::where('id_user', $professorcode)->first();
            $id_professeur = $professeur->id;
            $enseignant = Enseignant::findOrFail($id_professeur);
            return response()->json([
                'status_code' => 200,
                'items' => $enseignant
            ]);

        }
    }
    public function AffichageAll_President()
    {
        if (Gate::allows('role_president', Auth::user())) {

            $enseignant = Enseignant::all();
            return response()->json([
                'status_code' => 200,
                'items' => $enseignant
            ]);

        }
    }

    public function AffichagebyEtablissement_President($id_etablissement)
    {
        if (Gate::allows('role_president', Auth::user())) {

            $enseignant = Enseignant::where('etablissement_id', $id_etablissement)->get();
            return response()->json([
                'status_code' => 200,
                'items' => $enseignant
            ]);

        }
    }

    //Pour Directeur et administrateur etablissement
    public function Affichage_Administrateur()
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            $Admin = Auth::id();
            $Administrateur = Administrateur::where('id_user', $Admin)->first();
            $id_etablissement = $Administrateur->etablissement_id;
            $enseignant = Enseignant::where('etablissement', $id_etablissement)->get();
            return response()->json([
                'status_code' => 200,
                'items' => $enseignant
            ]);

        }
    }

    // Suppression d'un enseignant
    public function destroy($id)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {


            $enseignant = Enseignant::find($id);
            // changer l'etat 
            $enseignant->etat = false;

            $enseignant->save();

            return response()->json([
                'status_code' => 201,
                'success' => 'L\'enseignant est supprimé avec succès'
            ]);


        }

    }


    //Create un enseignant
    public function store(Request $request)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            $Admin = Auth::id();

            // Récupérer le professeur en utilisant le code de connexion
            $Administrateur = Administrateur::where('id_user', $Admin)->first();
            $id_etablissement = $Administrateur->etablissement;
            //Validate data coming from the user
            $fields = $request->validate([
                'nom' => 'required | string',
                'prenom' => 'required | string',
                'ppr' => 'required | string',

                'date_naissance' => 'required | string',
                'email' => 'required | string |unique:users,email',
                'password' => 'required | string|min:8 |confirmed',
                'designation' => 'required | string',
                'etat' => 'required'
            ]);
            $userExists = User::where('email', $fields['email'])->exists();

            if (!$userExists) {
                $user = User::create([
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
                    'etablissement' => $id_etablissement,
                    'id_grade' => $id_grade,
                    'id_user' => $user['id'],
                    'etat' => $fields['etat']
                ]);



                return response()->json([
                    'items' => $Enseignant
                ]);
            } else {
                //$enseignant = Enseignant::where('user_id', $userExists->id);
                //$enseignant->etat = false;
                return response()->json([
                    'erreur' => 'Enseignant déjà existant'
                ]);
            }
        } else {
            abort('403');
        }
    }
    //Enseignant change son profile 
    public function update_profile(Request $request)
    {
        if (Gate::allows('role_enseignant', Auth::user())) {

            $Ens = Auth::id();
            $Enseignant = Enseignant::where('id', $Ens)->first();

            $fields = $request->validate([
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'ppr' => 'required|string',
                'date_naissance' => 'required | date'
            ]);

            $Enseignant->nom = $fields['nom'];
            $Enseignant->prenom = $fields['prenom'];
            $Enseignant->ppr = $fields['ppr'];
            $Enseignant->date_naissance = $fields['date_naissance'];

            $Enseignant->save();

            return response()->json(['message' => 'Votre Informations sont mises à jour avec succès'], 200);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            $fields = $request->validate([
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'ppr' => 'required|string',
                'date_naissance' => 'required|string'
            ]);

            $enseignant = Enseignant::findOrFail($id);
            $enseignant->nom = $fields['nom'];
            $enseignant->prenom = $fields['prenom'];
            $enseignant->ppr = $fields['ppr'];
            $enseignant->date_naissance = $fields['date_naissance'];

            $enseignant->save();

            return response()->json(['message' => 'Informations personnelles de l\'enseignant mises à jour avec succès'], 200);
        }
    }

    public function changer_etablissement(Request $request, $id)
    {
        if (Gate::allows('role_admin_univ', Auth::user())) {

            $fields = $request->validate([

                'nom' => 'required | string',
                'ville' => 'required | string',
            ]);
            $etablissement = Etablissement::where('nom', $fields['nom'])->where('ville', $fields['ville'])->first();
            $enseignant = Enseignant::findOrFail($id);
            $enseignant->etablissement = $etablissement->id;

            $enseignant->save();

            return response()->json(['message' => 'Etablissement changé'], 200);
        }
    }



}