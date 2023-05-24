<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Etablissement;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller
{
    public function getAllInterventions_By_President()
    {
        $interventions = Intervention::where('visa_etb', 1)
            ->get(['id', 'intitule_intervention', 'annee__univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);


        return response()->json([
            'status_code' => 200,
            'status_message' => 'les interventions de l\'université ont été récupérés',
            'data' => $interventions
        ]);
    }

    public function getInterventionsByEtablissement_By_President($id_etablissement)
    {
        $interventions = Intervention::where('visa_etb', 1)
            ->where('etablissement_id', $id_etablissement)->get(['id', 'intitule_intervention', 'annee__univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);
        return response()->json([
            'status_code' => 200,
            'status_message' => 'les interventions de l\'université ont été récupérés',
            'data' => $interventions
        ]);

    }

    public function getInterventionsByenseignant_By_President($id_enseignant)
    {
        $interventions = Intervention::where('visa_etb', 1)
            ->where('enseignant_id', $id_enseignant)->get(['id', 'intitule_intervention', 'annee__univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures', 'enseignant_id']);
        return response()->json([
            'status_code' => 200,
            'status_message' => 'les interventions de l\'université ont été récupérés',
            'data' => $interventions
        ]);
    }

    public function getInterventionsByAnnee_By_President($anneeUniversitaire)
    {
        $interventions = Intervention::where('visa_etb', 1)
            ->where('annee__univ', $anneeUniversitaire)->get(['id', 'intitule_intervention', 'annee__univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);
        return response()->json([
            'status_code' => 200,
            'status_message' => 'les interventions de l\'université ont été récupérés',
            'data' => $interventions
        ]);

    }
    /* public function getAllInterventions_By_Enseignant()
    {
        $userID = auth()->user()->id;

        // Récupérer le enseignant en utilisant le code de connexion

        $interventions = Intervention::where('enseignant_id', $userID)
            ->get(['id', 'intitule_intervention', 'annee__univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures', 'visa_etb', 'visa_uae']);

        return response()->json([
            'status_code' => 200,
            'status_message' => 'les interventions de l\'université ont été récupérés',
            'data' => $userID
        ]);

    } */
    public function getAllInterventions_By_Enseignant()
    {
        // Vérifier si l'utilisateur est authentifié
        if (auth()->check()) {
            $userID = auth()->user()->id;

            // Récupérer l'enseignant en utilisant l'ID de l'utilisateur connecté
            $enseignant = Enseignant::where('user_id', $userID)->first();

            if (!$enseignant) {
                return response()->json([
                    'status_code' => 404,
                    'status_message' => 'Enseignant introuvable',
                    'data' => null
                ]);
            }

            // Récupérer les interventions de l'enseignant
            $interventions = Intervention::where('enseignant_id', $enseignant->id)
                ->get(['id', 'intitule_intervention', 'annee__univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures', 'visa_etb', 'visa_uae']);

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Les interventions de l\'enseignant ont été récupérées',
                'data' => $interventions
            ]);
        } else {
            return response()->json([
                'status_code' => 401,
                'status_message' => 'Utilisateur non authentifié',
                'data' => null
            ]);
        }
    }

    /* public function getInterventionsByEtablissement_By_Enseignant(Request $request, $id_etablissement)
    {
        $professorCode = $request['id'];

        // Récupérer le enseignant en utilisant le code de connexion
        $enseignant = Professor::where('id', $professorCode)->first();
        $id_enseignant = $enseignant->id;
        $interventions = Intervention::where('validation_Etablissement', 0)
            ->where('id_intervenant', $id_enseignant)
            ->where('id_etablissement', $id_etablissement)->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures']);
        ;


    } */
    /* public function getInterventionsByAnnee_By_Enseignant($anneeUniversitaire, Request $request)
    {
        $professorCode = $request['id'];

        // Récupérer le enseignant en utilisant le code de connexion
        $enseignant = Professor::where('id', $professorCode)->first();
        $id_enseignant = $enseignant->id;
        $interventions = Intervention::where('validation_Etablissement', 0)
            ->where('id_intervenant', $id_enseignant)
            ->where('Annee_univ', $anneeUniversitaire)->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures']);
        ;


    } */
    /* public function getAllInterventions_By_Directeur(Request $request)
    {
        $directeurcode = $request['id'];

        // Récupérer le enseignant en utilisant le code de connexion
        $directeur = Professor::where('id', $directeurcode)->first();
        $id_etablissement = $directeur->id_etablissement;
        $interventions = Intervention::where('id_Etablissement', $id_etablissement)
            ->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);

    } */
    /* public function getInterventionsByenseignant_By_Directeur(Request $request, $id_enseignant)
    {
        $directeurcode = $request['id'];

        // Récupérer le enseignant en utilisant le code de connexion
        $directeur = Professor::where('id', $directeurcode)->first();
        $id_etablissement = $directeur->id_etablissement;
        $interventions = Intervention::where('id_Etablissement', $id_etablissement)
            ->where('id_intervenant', $id_enseignant)->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);

    } */
    /* public function getInterventionsByAnnee_By_Directeur(Request $request, $anneeUniversitaire)
    {
        $directeurcode = $request['id'];

        // Récupérer le enseignant en utilisant le code de connexion
        $directeur = Professor::where('id', $directeurcode)->first();
        $id_etablissement = $directeur->id_etablissement;
        $interventions = Intervention::Intervention::where('id_Etablissement', $id_etablissement)
            ->where('Annee_univ', $anneeUniversitaire)->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);



    } */
    public function store(Request $request)
    {
        $data = $request->validate([
            'enseignant' => 'required',
            'etablissement' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'annee_universitaire' => 'required',
        ]);

        $intervenant = Enseignant::findOrFail($data['enseignant']);
        $etablissement = Etablissement::findOrFail($data['etablissement']);

        $intervention = Intervention::create([
            'id_intervenant' => $intervenant->id,
            'id_etablissement' => $etablissement->id,
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'annee_universitaire' => $data['annee_universitaire'],
        ]);

    }






    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'enseignant' => 'required',
            'etablissement' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'annee_universitaire' => 'required',
        ]);

        $intervenant = Enseignant::findOrFail($data['enseignant']);
        $etablissement = Etablissement::findOrFail($data['etablissement']);


        $intervention->intervenant_id = $data['enseignant_id'];
        $intervention->etablissement_id = $data['etablissement_id'];
        $intervention->date_debut = $data['date_debut'];
        $intervention->date_fin = $data['date_fin'];
        $intervention->annee_universitaire = $data['annee_universitaire'];
        $intervention->save();

        // Faites une redirection ou renvoyez une réponse appropriée
    }


    public function Valider_By_Directeur(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->validation_etablissement = 1;
        $intervention->save();

    }
    public function Non_Valider_By_Directeur(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->validation_etablissement = 0;
        $intervention->save();

    }
    public function Valider_By_President(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->validation_Universitaire = 1;
        $intervention->save();
    }
    public function Non_Valider_By_President(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->validation_Universitaire = 0;
        $intervention->save();
    }


    public function destroy($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->delete();

        // Faites une redirection ou renvoyez une réponse appropriée
    }
}