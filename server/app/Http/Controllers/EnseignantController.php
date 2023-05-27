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
    { if(!Gate::any(['role_admin_eta', 'role_directeur','role_president'])) {
        abort('403');
       }

        $enseignant = Enseignant::findOrFail($id);
        return response()->json([
            'status_code' => 200,
            'items' => $enseignant
        ]);

    }

    public function Profile()
    {
        if(!Gate::allows('role_enseignant')) {
            abort('403');
           }
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
        if(!Gate::allows('role_president')) {
            abort('403');
           }
        $enseignant = Enseignant::all();
        return response()->json([
            'status_code' => 200,
            'items' => $enseignant
        ]);

    }
    public function AffichagebyEtablissement_President($id_etablissement)
    {
        if(!Gate::allows('role_president')) {
            abort('403');
           }
        $enseignant = Enseignant::where('etablissement_id', $id_etablissement)->get();
        return response()->json([
            'status_code' => 200,
            'items' => $enseignant
        ]);

    }
    
    //Pour Directeur et administrateur etablissement
    public function Affichage_Administrateur()
    {
        if(!Gate::allows('role_admin_eta')) {
            abort('403');
           }
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
    { if(!Gate::allows('role_admin_eta')) {
        abort('403');
       }

        $enseignant = Enseignant::find($id);
        // changer l'etat 
        $enseignant->etat=false;

        $enseignant->save();

        return response()->json([
            'status_code' => 201,
            'success' => 'L\'enseignant est supprimé avec succès'
        ]);


    }




    //Create un enseignant
    public function store(Request $request)
    {
        if(!Gate::allows('role_admin_eta')) {
            abort('403');
           }
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
        $userExists = User::where('email', $fields['email'])->exists();
        
    if (!$userExists) {
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
        ]);}else{
           $enseignant=Enseignant::where('user_id',$userExists->id);
          $enseignant->etat=false;

        }
    }
    public function update_profile(Request $request)
    {
        $Enseignant=Auth::id();
        $Enseignant1=Enseignant::where('id',$Enseignant)->first();

        $fields = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ppr' => 'required|string',
           
        ]);
    

        $Enseignant1->nom = $fields['nom'];
        $Enseignant1->prenom = $fields['prenom'];
        $Enseignant1->ppr = $fields['ppr'];
       
    
        $Enseignant1->save();
    
        return response()->json(['message' => 'Votre INformations sont  mises à jour avec succès'], 200);
    }
    public function update(Request $request, $id)
    {   if (Gate::allows('role_admin_eta') || (Gate::allows('role_enseignant') && $id == Auth::id())) {
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
<<<<<<< HEAD
    public function changer_etablissement(Request $request, $id)
    {
        $fields = $request->validate([
           
                'nom'=>'required | string',
                'ville'=>'required | string',
        ]);
        $etablissement=Administrateur::where('nom',$required['nom'])->where('ville',$required['ville'])->first();
        $enseignant = Enseignant::findOrFail($id);
        $enseignant->id_etablissemen= $etablissement->id;
       
     
    
        $enseignant->save();
    
        return response()->json(['message' => 'Etablissement changé'], 200);
    }
=======
    else {
        // L'utilisateur n'a pas le rôle 'role_admin_eta' ou l'ID du compte à modifier
        // n'est pas égal à l'ID de l'utilisateur connecté
        // Interdire la modificationd'un autre compte
        abort(403); }
    }
}
>>>>>>> daef3291be2ec3a9d92741443420a5be3fc799fa

