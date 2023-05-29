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
   
    //affichage des informations
    public function show($id)
    {
        if(!Gate::any(['role_admin_eta', 'role_admin_univ']) ) {
            abort('403');
           }
        $Admin =Administrateur::findOrFail($id);
        return response()->json([
            'status_code' => 200 ,
            'items' => $Admin
        ]);
        
    }
    public function Profile()
    {
        if(!Gate::any(['role_admin_eta', 'role_directeur']) ) {
            abort('403');
           }
    
       
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
        if(!Gate::allows('role_president')) {
            abort('403');
               }
        $enseignant =Administrateur::all();
        return response()->json([
            'status_code' => 200 ,
            'items' => $enseignant
        ]);
        
    }
    public function AffichagebyEtablissement_President($id_etablissement)
    {   
         if(!Gate::allows('role_president')) {
        abort('403');
          }
        $administrateur =Administrateur::where('etablissement_id',$id_etablissement)->first();
        return response()->json([
            'status_code' => 200 ,
            'items' => $enseignant
        ]);
        
    }
 
  
    // Suppression d'un Administrateur
    public function destroy($id)
    {
        if(!Gate::any(['role_admin_eta', 'role_admin_univ']) ) {
            abort('403');
           }
        $admin = Administrateur::findOrFail($id);

        $admin->delete();
    
        return response()->json([
            'message' => 'L\'administrateur a été supprimé avec succès'
        ]);
    }
    
    public function store_Administrateur_Etablissement(Request $request)
    {     
        if(!Gate::allows('role_admin_univ')) {
            abort('403');
           }
        
        //Validate data coming from the user
        $fields = $request->validate([
            'nom' => 'required | string',
            'prenom' => 'required | string',
            'ppr' => 'required | string',
            'nom_etablissement'=>'required |string',
             'ville'=>'required |`string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |min:8|confirmed',
            
        ]);
       
        
   
        $user = User::create([
            
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'type' => 'Administrateur_Etablissement'
        ]);
        $Etablissement=Etablissemnt::where('nom',$fields['nom_etablissement'])->where('ville',$fields['ville'])->first();
  
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
       
    

    //Create un Administrateur
    public function store_Directeur(Request $request)
    {     
        if(!Gate::allows('role_admin_eta')) {
            abort('403');
           }
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
            'password' => 'required | string |min:8|confirmed',
            
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
        if(!Gate::any(['role_admin_eta', 'role_directeur']) ) {
            abort('403');
           }
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
        if(!Gate::any(['role_admin_eta', 'role_admin_univ']) ) {
            abort('403');
           }
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
        if(!Gate::allows('role_admin_univ')) {
            abort('403');
           }
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