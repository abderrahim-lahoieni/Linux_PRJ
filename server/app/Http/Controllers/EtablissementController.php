<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditEtablissementRequest;
use App\Models\Etablissement;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\CreateEtablissementRequest;

class EtablissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //Affichage le contenu des tous les etablissements
    /* public function index(Request $request)
    {   
        try{
            //Pour faire la pagination si on a plusieurs enregistrements dans la table entreprises
            $query = Etablissement::query();
            $perPage = 1;
            $page = $request->input('page',1);
            $search = $request->input('search');

            if($search){
                $query->whereRaw("code LIKE '%".$search."%'");
            }

            $total = $query->count();

            $result = $query->offset(($page -1) * $perPage)->limit($perPage)->get();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Les établissements ont été récupérées avec succès',
                'current_page' => $page ,
                'last_page' => ceil($total / $perPage),
                'items' => $result
            ]);
        }catch(Exception $e){
            return response()->json($e);
        }
    } */
    //Affichage  tous les etablissements
    public function index(){
        $etab = Etablissement::all();
        return response()->json([
            'status_code' => 200 ,
            'items' => $etab
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEtablissementRequest $request)
    {
        //Utilisation de try and catch pour refuser l'accès au base de données si le serveur n'est pas démarée 
        try {

            $etab = new Etablissement();

            //Get the informations from the user by request
            //les noms des champs de request sont les noms des input
            $etab->code = $request->code;
            $etab->nom = $request->nom;
            $etab->telephone = $request->telephone;
            $etab->faxe = $request->faxe;
            $etab->ville = $request->ville;
            $etab->nbr_enseignants = $request->nbr_enseignants;

            $etab->save();

            return response()->json([
                'status_code' => 201,
                'status_message' => 'L etablissement est ajouté avec succès',
                'data' => $etab
            ]);

        } catch (Exception $e) {
            return response()->json($e);
        }

    }

    /**
     * Displ ay the specified resource.
     */
    public function show($id)
    {

        try{
            $etab =Etablissement::find($id);

            return response()->json([
                'status_code' => 201,
                'status_message' => 'L etablissement est bien trouvée avec succès',
                'data' => $etab
            ]);
        }catch(Exception $e){
                return response()->json($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etablissement $etablissement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditEtablissementRequest $request,$id)
    {
        try {

            //Chercher l'enregistrement avec id passée par l'utilisateur
            $etab = Etablissement::find($id);

            $etab->code = $request->code;
            $etab->nom = $request->nom;
            $etab->telephone = $request->num_tel;
            $etab->faxe = $request->faxe;
            $etab->ville = $request->ville;
            $etab->nbr_enseignants = $request->nbre_enseignant;

            $etab->save(); //Enregistrement de nouvelles valeurs

            return response()->json([
                'status_code' => 201,
                'status_message' => 'L etablissement est editée avec succès',
                'data' => $etab
            ]);

        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Etablissement $etablissement)
    {
            try{
                $etablissement->delete();
                return response()->json([
                'status_code' => 200,
                'status_message' => 'L etablissement est supprimée avec succès',
                'data' => $etablissement
                ]);
            }catch(Exception $e){
                return response()->json($e);
            }
    }
}