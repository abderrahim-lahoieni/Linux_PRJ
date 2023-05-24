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
    public function getAllInterventions_By_Enseignant()
    {
        $professorcode =Auth::user()->id;

        // Récupérer le professeur en utilisant le code de connexion
        $professeur = Enseignant::where('id_user', $professorCode->id)->first();
        $id_professeur = $professeur->id;
        $interventions = Intervention::where('validation_Etablissement', 0)
            ->where('id_intervenant', $id_professeur)
            ->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures']);
        ;

    }
    public function getInterventionsByEtablissement_By_Enseignant($id_etablissement)
    {
        $professorcode =Auth::user()->id;

        // Récupérer le professeur en utilisant le code de connexion
        $professeur = Enseignant::where('id_user', $professorCode->id)->first();
        $id_professeur = $professeur->id;
        $interventions = Intervention::where('validation_Etablissement', 0)
            ->where('id_intervenant', $id_professeur)
            ->where('id_etablissement', $id_etablissement)->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures']);
        ;


    }
    public function getInterventionsByAnnee_By_Enseignant($anneeUniversitaire)
    {
        $professorcode =Auth::user()->id;

       
        $professeur = Enseignant::where('id_user', $professorCode->id)->first();
        $id_professeur = $professeur->id;
        $interventions = Intervention::where('validation_Etablissement', 0)
            ->where('id_intervenant', $id_professeur)
            ->where('Annee_univ', $anneeUniversitaire)->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures']);
        ;


    }
  
    public function getInterventionsByProfesseur_By_Administrateur($id_professeur)
    {
        $admincode =Auth::user()->id;

        $admin = Administrateur::where('id_user', $admincode)->first();
        $id_etablissement = $admin->id_etablissement;
        $interventions = Intervention::where('id_Etablissement', $id_etablissement)
            ->where('id_intervenant', $id_professeur)->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);

    }
    public function getAllInterventions_By_Administrateur()
    {
        $admincode =Auth::user()->id;

        
        $admin = Administrateur::where('id_user', $admincode)->first();
        
        $id_etablissement = $admin->id_etablissement;
        $interventions = Intervention::where('id_Etablissement', $id_etablissement)
            ->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);

    }
    
    public function getInterventionsByAnnee_By_Administrateur($anneeUniversitaire)
    {
        $admincode =Auth::user()->id;

        
        $admin = Administrateur::where('id_user', $admincode)->first();
        
        $id_etablissement = $admin->id_etablissement;
        $interventions = Intervention::where('id_Etablissement', $id_etablissement)
        ->where('Annee_univ', $anneeUniversitaire) 
        ->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);

    }
    public function getInterventionsBySemestre_By_Administrateur($anneeUniversitaire,$semestre)
    {  $admincode =Auth::user()->id;

        
        $admin = Administrateur::where('id_user', $admincode)->first();
        
        $id_etablissement = $admin->id_etablissement;
        $interventions = Intervention::Intervention::where('id_Etablissement', $id_etablissement)
            ->where('Annee_univ', $anneeUniversitaire)
            ->where('Annee_univ', $semestre)
            ->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);



    }
    public function getInterventionsByProfesseur_By_Directeur($id_professeur)
    {
        $directeurcode=Auth::user()->id;

        $directeur = Administrateur::where('id_user',$directeurcode )->first();
        $id_etablissement = $directeur->id_etablissement;
        $interventions = Intervention::where('id_Etablissement', $id_etablissement)
            ->where('id_intervenant', $id_professeur)->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);

    }
    public function getInterventionsByAnnee_By_Directeur($anneeUniversitaire)
    { 
        $directeurcode=Auth::user()->id;

        
        $directeur = Administrateur::where('id_user',$directeurcode )->first();
       
        $id_etablissement = $directeur->id_etablissement;
        $interventions = Intervention::Intervention::where('id_Etablissement', $id_etablissement)
            ->where('Annee_univ', $anneeUniversitaire)
            ->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);


    }
    public function getInterventionsBySemestre_By_Directeur($anneeUniversitaire,$semestre)
    { 
        $directeurcode=Auth::user()->id;

        
        $directeur = Administrateur::where('id_user',$directeurcode )->first();
        $id_etablissement = $directeur->id_etablissement;
        $interventions = Intervention::Intervention::where('id_Etablissement', $id_etablissement)
            ->where('Annee_univ', $anneeUniversitaire)
            ->where('Annee_univ', $semestre)
            ->get(['id', 'intitule_intervention', 'Annee_univ', 'Semestre', 'Date_debut', 'Date_fin', 'Nbr_heures', 'validation_Etablissement']);



    }
    public function store()
    {    $admincode =Auth::user()->id;

        
        $admin = Administrateur::where('id_user', $admincode)->first();
        
        $id_etablissement = $admin->id_etablissement;
        $data = $request->validate([
            'enseignant' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'annee_universitaire' => 'required',
        ]);

        $intervenant = Enseignant::findOrFail($data['enseignant']);
        

        $intervention = Intervention::create([
            'id_intervenant' => $intervenant->id,
            'id_etablissement' => $id_etablissement,
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'annee_universitaire' => $data['annee_universitaire'],
        ]);

    }






    public function update($id)
    {

        $admincode =Auth::user()->id;

        
        $admin = Administrateur::where('id_user', $admincode)->first();
        
        $id_etablissement = $admin->id_etablissement;
        $data = $request->validate([
            'enseignant' => 'required',
           
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'annee_universitaire' => 'required',
        ]);

        $intervenant = Enseignant::findOrFail($data['enseignant']);
        


        $intervention->intervenant_id = $data['enseignant_id'];
        $intervention->etablissement_id =$id_etablissement ;
        $intervention->date_debut = $data['date_debut'];
        $intervention->date_fin = $data['date_fin'];
        $intervention->annee_universitaire = $data['annee_universitaire'];
        $intervention->save();

        // Faites une redirection ou renvoyez une réponse appropriée
    }


    public function Valider_By_Directeur($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->validation_etablissement = 1;
        $intervention->save();

    }
    public function Non_Valider_By_Directeur($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->validation_etablissement = 0;
        $intervention->save();

    }
    public function Valider_By_President($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->validation_Universitaire = 1;
        $intervention->save();
        $intervention->notify(new InterventionValidationNotification($intervention->Intitule_Intervention));

    }
    public function Non_Valider_By_President($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->validation_Universitaire = 0;
        $intervention->save();
        $intervention->notify(new InterventionValidationNotification($intervention->Intitule_Intervention));
    }


    public function destroy($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->delete();

        // Faites une redirection ou renvoyez une réponse appropriée
    }
        
}