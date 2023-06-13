<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Notifications\ValidationIntervention;
use App\Notifications\NonValidationIntervention;

class InterventionController extends Controller
{
    public function getAllInterventions_By_President()
    {
        if (Gate::allows('role_president', Auth::user())) {

            $interventions = Intervention::where('visa_etb', 1)
                ->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);


            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);
        } else {
            abort('403');

        }
    }
    public function getInterventionsByEtablissement_By_President($id_etablissement)
    {
        if (Gate::allows('role_president', Auth::user())) {

            $interventions = Intervention::where('visa_etb', 1)
                ->where('id_etab', $id_etablissement)->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);
            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);

        } else {
            abort('403');
        }
    }

    public function getInterventionsByenseignant_By_President($id_enseignant)
    {
        if (Gate::allows('role_president', Auth::user())) {

            $interventions = Intervention::where('visa_etb', 1)
                ->where('id_intervenant', $id_enseignant)->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);
            $enseignant = Enseignant::where('id_user', $interventions->id_intervenant);
            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'interventions' => $interventions,
                'enseignant' => $enseignant
            ]);
        } else {
            abort('403');
        }
    }

    public function getInterventionsByAnnee_By_President($anneeUniversitaire)
    {
        if (Gate::allows('role_president', Auth::user())) {

            $interventions = Intervention::where('visa_etb', 1)
                ->where('annee_univ', $anneeUniversitaire)->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);
            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);

        } else {
            abort('403');
        }
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
    /* public function getAllInterventions_By_Enseignant()
    {
        $professorcode =Auth::user()->id;

        // Récupérer le professeur en utilisant le code de connexion
        $professeur = Enseignant::where('id_user', $professorCode->id)->first();
        $id_professeur = $professeur->id;
        $interventions = Intervention::where('validation_Etablissement', 0)
            ->where('id_intervenant', $id_professeur)
            ->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures']);
        ;

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
    } */
    /* public function getAllInterventions_By_Enseignant()
    {       

        if (Auth::check()){
            $professorcode = Auth::user()->id;

        // Récupérer le professeur en utilisant le code de connexion
        $professeur = Enseignant::where('user_id', $professorcode)->first();

        if (!$professeur) {
            return response()->json([
                'status_code' => 404,
                'status_message' => 'Enseignant introuvable',
                'data' => null
            ]);
        }

        $id_professeur = $professeur->id;
        $interventions = Intervention::where('enseignant_id', $id_professeur)
            ->get(['id', 'intitule_intervention', 'annee__univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);

        return response()->json([
            'status_code' => 200,
            'status_message' => 'Toutes les interventions de l\'enseignant',
            'data' => $interventions
        ]);
        }
        else{
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Toutes les interventions de l\'enseignant',
                'data' => 'unauthaurize'
            ]);
        }
        
    } */

    public function getAllInterventions_By_Enseignant()
    {
        if (Gate::allows('role_enseignant', Auth::user())) {

            // Vérifier l'authentification avec le middleware
            // $this->middleware('auth:sanctum');

            // Récupérer l'ID de l'utilisateur authentifié
            $professorId = Auth::id();

            // Récupérer le professeur en utilisant l'ID de l'utilisateur
            $professeur = Enseignant::where('id_user', $professorId)->first();

            if (!$professeur) {
                // Gérer le cas où le professeur n'est pas trouvé
                return response()->json(['error' => 'Professeur introuvable'], 404);
            }

            $id_professeur = $professeur->id;

            // Récupérer les interventions du professeur
            $interventions = Intervention::where('id_intervenant', $id_professeur)
                ->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);

            return response()->json($interventions, 200);
        } else {
            abort('403');
        }
    }


    public function getInterventionsByEtablissement_By_Enseignant($id_etablissement)
    {
        if (Gate::allows('role_enseignant', Auth::user())) {

            $professorcode = Auth::id();

            // Récupérer le professeur en utilisant le code de connexion
            $professeur = Enseignant::where('id_user', $professorcode)->first();
            $id_professeur = $professeur->id;
            $interventions = Intervention::where('id_intervenant', $id_professeur)
                ->where('id_etab', $id_etablissement)->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);

            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);

        } else {
            abort('403');
        }
    }


    public function getInterventionsByAnnee_By_Enseignant($anneeUniversitaire)
    {
        if (Gate::allows('role_enseignant', Auth::user())) {

            $professorcode = Auth::id();


            $professeur = Enseignant::where('id_user', $professorcode)->first();
            $id_professeur = $professeur->id;
            $interventions = Intervention::where('id_intervenant', $id_professeur)
                ->where('annee_univ', $anneeUniversitaire)->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);

            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);
        } else {
            abort('403');
        }
    }


    public function getInterventionsByProfesseur_By_Administrateur($id_professeur)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            //Administrateur Etablissement
            $admincode = Auth::id();

            $admin = Administrateur::where('id_user', $admincode)->first();
            $id_etablissement = $admin->etablissement_id;
            $interventions = Intervention::where('id_etab', $id_etablissement)
                ->where('id_intervenant', $id_professeur)->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);
            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);
        } else {
            abort('403');
        }
    }

    public function getAllInterventions_By_Administrateur()
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            $admincode = Auth::id();


            $admin = Administrateur::where('id_user', $admincode)->first();

            $id_etablissement = $admin->etablissement_id;
            $interventions = Intervention::where('id_etab', $id_etablissement)
                ->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);

            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);
        } else {
            abort('403');
        }
    }

    public function getInterventionsByAnnee_By_Administrateur($anneeUniversitaire)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            $admincode = Auth::id();


            $admin = Administrateur::where('id_user', $admincode)->first();

            $id_etablissement = $admin->etablissement_id;
            $interventions = Intervention::where('id_etab', $id_etablissement)
                ->where('annee_univ', $anneeUniversitaire)
                ->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);
            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);
        } else {
            abort('403');
        }
    }

    public function getInterventionsBySemestre_By_Administrateur($anneeUniversitaire, $semestre)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            $admincode = Auth::id();


            $admin = Administrateur::where('id_user', $admincode)->first();

            $id_etablissement = $admin->etablissement_id;
            $interventions = Intervention::where('id_etab', $id_etablissement)
                ->where('annee_univ', $anneeUniversitaire)
                ->where('semestre', $semestre)
                ->get(['id', 'intitule_intervention', 'annee_univ', 'semestre', 'date_debut', 'date_fin', 'nbr_heures']);

            return response()->json([
                'status_code' => 200,
                'status_message' => 'les interventions de l\'université ont été récupérés',
                'data' => $interventions
            ]);

        } else {
            abort('403');
        }
    }


    public function store(Request $request)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            $admincode = Auth::id();


            $admin = Administrateur::where('id_user', $admincode)->first();

            $id_etablissement = $admin->etablissement;
            $data = $request->validate([
                'intitule_intervention' => 'required | string',
                'annee_univ' => 'required | string',
                'semestre' => 'required | string',
                'date_debut' => 'required | date',
                'date_fin' => 'required | date',
                'nbr_heures' => 'required | integer',
                'ppr_enseignant' => 'required | string'
            ]);

            $intervenant = Enseignant::where('ppr', $data['ppr_enseignant'])->first();
            if ($intervenant->etat) {
                $intervention = Intervention::create([
                    'intitule_intervention' => $data['intitule_intervention'],
                    'annee_univ' => $data['annee_univ'],
                    'semestre' => $data['semestre'],
                    'date_debut' => $data['date_debut'],
                    'date_fin' => $data['date_fin'],
                    'nbr_heures' => $data['nbr_heures'],
                    'id_etab' => $id_etablissement,
                    'id_intervenant' => $intervenant['id']
                ]);

                return response()->json([
                    'status_code' => 200,
                    'status_message' => 'l\'intervention est bien crée',
                    'data' => $intervention
                ]);
            } else {
                return response()->json([
                    'status_code' => 401,
                    'status_message' => 'Impossible de ajouter cette intervention ,Ce professeur est supprimé',
                ]);
            }

        } else {
            abort('403');
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {


            $intervention = Intervention::find($id);
            $data = $request->validate([
                'intitule_intervention' => 'required | string',
                'annee_univ' => 'required | string',
                'semestre' => 'required | string',
                'date_debut' => 'required | date',
                'date_fin' => 'required | date',
                'nbr_heures' => 'required | integer',
                'ppr_enseignant' => 'required | string'
            ]);

            $intervenant = Enseignant::where('ppr', $data['ppr_enseignant'])->first();

            $intervention->intitule_intervention = $data['intitule_intervention'];
            $intervention->annee_univ = $data['annee_univ'];
            $intervention->semestre = $data['semestre'];
            $intervention->date_debut = $data['date_debut'];
            $intervention->date_fin = $data['date_fin'];
            $intervention->nbr_heures = $data['nbr_heures'];
            $intervention->id_intervenant = $intervenant->id;

            $intervention->save();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'modifié avec succès',
                'data' => $intervention
            ]);

        } else {
            abort('403');
        }
    }


    public function Valider_By_Directeur($id)
    {
        if (Gate::allows('role_directeur', Auth::user())) {

            $intervention = Intervention::findOrFail($id);
            $intervention->visa_etb = 'true';
            $intervention->save();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'modifié avec succès'
            ]);
        } else {
            abort('403');
        }
    }

    public function Non_Valider_By_Directeur($id)
    {
        if (Gate::allows('role_directeur', Auth::user())) {

            $intervention = Intervention::findOrFail($id);
            $visa_uae = $intervention->visa_uae;
            if ($visa_uae == 'true') {
                return response()->json([
                    'status_code' => 401,
                    'status_message' => 'Impossible de modifier la validation'
                ]);
            } else {
                $intervention->visa_etb = 'false';
                $intervention->save();
                return response()->json([
                    'status_code' => 200,
                    'status_message' => 'modifié avec succès'
                ]);
            }
        } else {
            abort('403');
        }
    }

    public function Valider_By_President($id)
    {
        if (Gate::allows('role_president', Auth::user())) {

            $intervention = Intervention::findOrFail($id);
            $intervention->visa_uae = 'true';
            $intervention->save();
            $Intitule = $intervention->intitule_intervention;
            //$intervention->notify(new ValidationIntervention($Intitule));

            return response()->json([
                'status_code' => 200,
                'status_message' => 'modifié avec succès'
            ]);


        } else {
            abort('403');
        }
    }
    public function Non_Valider_By_President($id)
    {
        if (Gate::allows('role_president', Auth::user())) {

            $intervention = Intervention::findOrFail($id);
            $intervention->visa_uae = 'false';
            $intervention->save();
            $Intitule = $intervention->intitule_intervention;
            //$intervention->notify(new NonValidationIntervention($Intitule));
            return response()->json([
                'status_code' => 200,
                'status_message' => 'modifié avec succès'
            ]);
        } else {
            abort('403');
        }
    }


    public function destroy($id)
    {
        if (Gate::allows('role_admin_eta', Auth::user())) {

            $intervention = Intervention::findOrFail($id);
            $intervention->delete();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Supprimé avec succès'
            ]);
            // Faites une redirection ou renvoyez une réponse appropriée
        } else {
            abort('403');
        }
    }

}