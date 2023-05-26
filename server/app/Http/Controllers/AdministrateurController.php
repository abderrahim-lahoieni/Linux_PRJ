<?php

namespace App\Http\Controllers;

use App\Models\Administrateur;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Etablissement;
use Exception;

class AdministrateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return response()->json([
            'status_code' => 201,
                'items' => Administrateur::all()
            ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { if(!Gate::allows('role_admin_univ')) {
        abort('403');
       }

        //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',
            'nom_etablissement' => 'required | string',
            'type' => 'required | string',  //Type faut qu'il faut DIRECTEUR,ADMINISTRATEUR_ETA
        ]);
        $user = User::create([
            'name' => $fields['nom'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'type' => $fields['type']
        ]);
        
            $etablissement = Etablissement::where('nom', $fields['nom_etablissement'])->first();
            $id = $etablissement->id;

            $Administrateur = Administrateur::create([
                'nom' => $fields['nom'],
                'prenom' => $fields['prenom'],
                'ppr' => $fields['ppr'],

                'etablissement_id' => $id,
                'user_id' => $user->id
            ]);
            return response()->json([
                'status_code' => 200,
                'items' => $Administrateur
            ]);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    { if(!Gate::allows('role_admin_univ')) {
        abort('403');
       }
        $admin = Administrateur::find($id);
        
        return response()->json([
            'status_code' => 200,
            'items' => $admin
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrateur $administrateur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Administrateur $administrateur)
    { if (Gate::allows('role_admin_univ') || (Gate::any(['role_admin_eta', 'role_directeur']) && $id == Auth::id())) {
        
        //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed'  //Type faut qu'il faut DIRECTEUR,ADMINISTRATEUR_ETA
        ]);
        $user = User::create([
            $administrateur->nom => $fields['nom'],
            $administrateur->email  => $fields['email'],
            $administrateur->password  => bcrypt($fields['password']),
            $administrateur->type  => $fields['type']
        ]);
        
            $etablissement = Etablissement::where('nom', $fields['nom_etablissement'])->first();
            $id = $etablissement->id;

            $Administrateur = Administrateur::create([
                'nom' => $fields['nom'],
                'prenom' => $fields['prenom'],
                'ppr' => $fields['ppr'],

                'etablissement_id' => $id,
                'user_id' => $user->id
            ]);
            return response()->json([
                'status_code' => 200,
                'items' => $Administrateur
            ]);
        }
        else {
            // L'utilisateur n'a pas le rôle 'role_admin_univ' ou l'ID du compte à modifier
            // n'est pas égal à l'ID de l'utilisateur connecté
            // Interdire la modificationd'un autre compte
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(!Gate::allows('role_admin_univ')) {
            abort('403');
           }
            
        $admin = Administrateur::find($id);
        // Supprimez l'enseignant de la table "enseignant"
        $admin->delete();
        
        // Supprimez également l'utilisateur associé de la table "users"
        //Trigger 
        $user = $admin->user_id;
        $user1 = User::where('id', $user)->first();
        $user1->delete();
        
        return response()->json([
            'status_code' => 201 ,
            'success' => 'L\'enseignant est supprimé avec succès'
        ]);
    }
}