<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use App\Models\Grade;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\EditEtablissementRequest;
use Exception;

class EnseignantController extends Controller
{
<<<<<<< HEAD
   
    //affichage des informations
    public function show($id)
    {
        $Admin =Administrateur::findOrFail($id);
=======
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
        
>>>>>>> daef3291be2ec3a9d92741443420a5be3fc799fa
        return response()->json([
            'status_code' => 200 ,
            'items' => $Admin
        ]);
        
    }
    public function Profile()
    {
       
        $Admincode =Auth::id();
       
        $Admin = Administrateur::where('user_id', $Admincode)->first();
        $id_Admin = $Admin->id;
        $Administrateur =Administrateur ::findOrFail( $id_professeur);
        return response()->json([
            'status_code' => 200 ,
            'items' =>$Administrateur,
        ]);
   
   }
    public function AffichageAll_President()
    {   
        $enseignant =Administrateur::all();
        return response()->json([
            'status_code' => 200 ,
            'items' => $enseignant
        ]);
        
    }
<<<<<<< HEAD
    public function AffichagebyEtablissement_President($id_etablissement)
    {   
        $administrateur =Administrateur::where('etablissement_id',$id_etablissement)->first();
        return response()->json([
            'status_code' => 200 ,
            'items' => $enseignant
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

=======

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Administrateur $administrateur)
    { if (Gate::allows('role_admin_univ') || (Gate::any(['role_admin_eta', 'role_directeur']) && $id == Auth::id())) {
>>>>>>> daef3291be2ec3a9d92741443420a5be3fc799fa
        
        //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',
            'nom_etablissement'=>'required |string',
             'ville'=>'required |`string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',
            
        ]);
       
        
   
        $user = User::create([
            
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'type' => 'Administrateur_Etablissement'
        ]);
<<<<<<< HEAD
        $Etablissement=Etablissemnt::where('nom',$fields['nom_etablissement'])->where('ville',$fields['ville'])->first();
  
        $Administrateur =Administrateur::create([
            'nom' => $fields['nom'],
            'prenom' => $fields['prenom'],
            'ppr' => $fields['ppr'],
            
            
            'etablissement_id'=>$id_etablissement,
    
            'user_id'=>$user['id'],
        ]);
    
=======
        
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
        
>>>>>>> daef3291be2ec3a9d92741443420a5be3fc799fa
        return response()->json([
            'items' => $Administrateur,
        ]);
    }
       
    

    //Create un Administrateur
    public function store_Directeur(Request $request)
    {     

        $Admin =Auth::id();

        // Récupérer l'Administrateur en utilisant le code de connexion
        $Administrateur =Administrateur::where('user_id',$Admin)->first();
        $id_etablissement =$Administrateur->id_etablissement ;
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
  
        $Administrateur =Administrateur::create([
            'nom' => $fields['nom'],
            'prenom' => $fields['prenom'],
            'ppr' => $fields['ppr'],
            
            
            'etablissement_id'=>$id_etablissement,
    
            'user_id'=>$user['id'],
        ]);
    
        return response()->json([
            'items' => $Administrateur,
        ]);
    }
    public function update_profile(Request $request)
    {
        $admin=Auth::id();
        $administrateur=Administrateur::where('id',$admin)->first();

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
    
        $Administrateur= Administrateur::findOrFail($id);
        $Administrateur->nom = $fields['nom'];
        $Administrateur->prenom = $fields['prenom'];
        $Administrateur->ppr = $fields['ppr'];
       
    
        $Administrateur->save();
    
        return response()->json(['message' => 'Informations personnelles de l\'Administrateur sont mises à jour avec succès'], 200);
    }
    public function changer_etablissement(Request $request, $id)
    {
        $fields = $request->validate([
           
                'nom'=>'required | string',
                'ville'=>'required | string',
        ]);
        $etablissement=Etablissement::where('nom',$required['nom'])->where('ville',$required['ville'])->first();
        $Administrateur= Administrateur::findOrFail($id);
        $Administrateur->id_etablissement= $etablissement->id;
       
     
    
        $Administrateur->save();
    
        return response()->json(['message' => 'Etablissement changé'], 200);
    }
   
    
}