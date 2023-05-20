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
    public function index()
    {
        return Etablissement::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $etab = new Etablissement();
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
            $etab->num_tel = $request->num_tel;
            $etab->faxe = $request->faxe;
            $etab->ville = $request->ville;
            $etab->nbre_enseignant = $request->nbre_enseignant;

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
            $etab->num_tel = $request->num_tel;
            $etab->faxe = $request->faxe;
            $etab->ville = $request->ville;
            $etab->nbre_enseignant = $request->nbre_enseignant;

            $etab->save(); //Enregistrement de nouvelles valeurs

            return response()->json([
                'status_code' => 201,
                'status_message' => 'L etablissement est edité avec succès',
                'data' => $etab
            ]);

        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Etablissement $etablissement)
    {
        //
    }
}