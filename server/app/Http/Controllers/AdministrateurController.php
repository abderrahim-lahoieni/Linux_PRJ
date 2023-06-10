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
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EditEtablissementRequest;

class AdministrateurController extends Controller
{

    //affichage des informations
    public function show($id)
    {

        if (Gate::any(['role_admin_univ', 'role_admin_eta'], Auth::user())) {


            //|| !Gate::allows('role_admin_eta', Auth::user())
            // if (!Gate::allows('role_admin_univ', Auth::user()) ) {
            //     abort('403');
            // }

            $Admin = Administrateur::findOrFail($id);

            return response()->json([
                'status_code' => 200,
                'items' => $Admin
            ]);

        }else{
            abort('403');
        }
    }
    public function Profile()
    {
        if (Gate::any(['role_admin_eta', 'role_directeur'], Auth::user())) {


            $Admincode = Auth::id();
            $Admin = Administrateur::where('user_id', $Admincode)->first();
            $id_Admin = $Admin->id;
            $Administrateur = Administrateur::findOrFail($id_Admin);
            return response()->json([
                'status_code' => 200,
                'items' => $Administrateur,
            ]);
        }else{
            abort('403');
        }
    }
    public function AffichageAll_President()
    {
        if (Gate::allows('role_president', Auth::user())) {
          
        $enseignant = Administrateur::all();
        return response()->json([
            'status_code' => 200,
            'items' => $enseignant
        ]);

    }else{
        abort('403');
    }
}
    public function AffichagebyEtablissement_President($id_etablissement)
    {
        if (Gate::allows('role_president',Auth::user())) {
            
        $administrateur = Administrateur::where('etablissement_id', $id_etablissement)->first();
        return response()->json([
            'status_code' => 200,
            'items' => $administrateur
        ]);

    }else{
        abort('403');
    }
    }

    public function store_Administrateur_Etablissement(Request $request)
    {

        if (Gate::allows('role_admin_univ', Auth::user())) {


            //Validate data coming from the user
            $fields = $request->validate([
                'nom' => 'required | string',
                'prenom' => 'required | string',
                'ppr' => 'required | string',
                'nom_etablissement' => 'required |string',
                'ville' => 'required | string',
                'email' => 'required | string |unique:users,email',
                'password' => 'required | string |min:8|confirmed',

            ]);


            $user = User::create([

                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
                'type' => 'ADMINISTRATEUR_ETA'
            ]);

            $Etablissement = Etablissement::firstWhere([
                'nom' => 'Faculté des sciences et techniques',
                'ville' => 'tanger'
            ]);
            
            $id_etablissement = $Etablissement->id;
            $Administrateur = Administrateur::create([
                'nom' => $fields['nom'],
                'prenom' => $fields['prenom'],
                'ppr' => $fields['ppr'],
                'etablissement' => $id_etablissement,
                'id_user' => $user['id']
            ]);

            return response()->json([
                'status_code' => 200,
                'items' => $Administrateur
            ]);
        }else{
            abort('403');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Gate::any(['role_admin_eta', 'role_admin_univ'], Auth::user())) {
          

        $admin = Administrateur::find($id);
        // Supprimez l'enseignant de la table "enseignant"
        $admin->delete();

        // Supprimez également l'utilisateur associé de la table "users"
        //Trigger 
        $user = $admin->user_id;
        $user1 = User::where('id', $user)->first();
        $user1->delete();

        return response()->json([
            'status_code' => 200,
            'items' => "Administrateur est supprimé avec succès",
        ]);
    }else{
        abort('403');
    }


    }
    //Create un Administrateur
    public function store_Directeur(Request $request)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

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
                'password' => 'required | string |min:8|confirmed',

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
        }else{
            abort('403');
        }
    }
    public function update_profile(Request $request)
    {
        if (Gate::any(['role_admin_eta', 'role_directeur'], Auth::user())) {
         
        $admin = Auth::id();
        $administrateur = Administrateur::where('id', $admin)->first();
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
    }else{
        abort('403');
    }
}
    public function update(Request $request, $id)
    {
        if (Gate::any(['role_admin_eta', 'role_admin_univ'], Auth::user())) {
         
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
    }else{
        abort('403');
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
        $Administrateur = Administrateur::findOrFail($id);
        $Administrateur->etablissement = $etablissement->id;

        $Administrateur->save();

        return response()->json(['message' => 'Etablissement changé'], 200);
    }else{
        abort('403');
    }
}

}