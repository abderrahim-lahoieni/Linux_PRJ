<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdministrateurController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\InterventionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});








Route::get('/paiements', [PaiementController::class, 'index']);

//-------Paiement---------
Route::get('/paiements', [PaiementController::class, 'index']);
Route::delete('/paiements/{id}', [PaiementController::class, 'destroy']);



//-------Grade-----------
Route::get('/grades', [GradeController::class, 'index']);
Route::get('/grades/{id}', [GradeController::class, 'show']);
Route::post('/grades/create', [GradeController::class, 'store']);
Route::post('/grades/edit/{id}', [GradeController::class, 'update']);

//--------Etablissement----------

//Affichage des etablissements
Route::get('/etablissements', [EtablissementController::class, 'index']);
//Creation d'une etablissement
Route::post('/etablissements/create', [EtablissementController::class, 'store']);
//Modification d'une etablissement
Route::post('/etablissements/edit/{id}', [EtablissementController::class, 'update']);
//Suppression d'une etablissement
Route::delete('/etablissements/{etablissement}', [EtablissementController::class, 'delete']);
//Affichage d'une etablissement
Route::get('/etablissements/{id}', [EtablissementController::class, 'show']);


//affichage d'un enseignant:

Route::get('/enseignants/{id}', [EnseignantController::class, 'show']);
Route::delete('/enseignants/{id}', [EnseignantController::class, 'destroy']);



//Public routes
Route::post('/login', [AuthController::class, 'login']);



//forget Password

Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
Route::post('/resetPassword', [AuthController::class, 'resetPassword']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::get('/administrateur/{id}', [AdministrateurController::class, 'show']);

    Route::post('/register', [AuthController::class, 'register']);

    //Put your routes here, Just that's need from the user to be connected
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::post('/administrateur_univ/create/administrateur_etb', [AdministrateurController::class, 'store_Administrateur_Etablissement']);
    Route::post('/administrateur_etb/create/directeur', [AdministrateurController::class, 'store_Directeur']);

    Route::get('/administrateur_etb', [AdministrateurController::class, 'Profile']);
    Route::get('/directeur', [AdministrateurController::class, 'Profile']);
    //Route::get('/administrateurs/{id}', [AdministrateurController::class, 'show']);
    Route::delete('/administrateur_univ/administrateur_etb/{id}', [AdministrateurController::class, 'destroy']);
    Route::delete('/administrateur_etb/directeur/{id}', [AdministrateurController::class, 'destroy']);
    // Route pour créer une nouvelle intervention

    Route::get('/enseignants', [EnseignantController::class, 'Profile']);
    Route::get('/administrateur_etb/enseignants', [EnseignantController::class, 'index']);
    Route::get('/administrateur_etb/enseignants/{id_etab}', [EnseignantController::class, 'getEtab']);
    //Affichage les enseignants par les administrateurs etablissement
    Route::get('/directeur/enseignants', [EnseignantController::class, 'Affichage_Administrateur']);
    //Affichage les enseignants par le directeur
    Route::get('/administrateur_etb/enseignants', [EnseignantController::class, 'Affichage_Administrateur']);

    Route::get('/administrateur_etb/directeur', [AdministrateurController::class, 'AfficherDirecteur']);
    
    Route::get('/administrateur_univ/president',[AdministrateurController::class, 'AfficherPresident']);
    Route::get('/enseignant/paiement/{Annee}', [PaiementController::class, 'Afichage_Mon_Payement']);

    //Route::get('/enseignant/Calcul_Salaire/{Annee}', [PaiementController::class, 'Calculer_Mon_Salaire']);
    Route::get('/enseignant/paiement/Total/{Annee}',[PaiementController::class , 'Afficher_salaire_Total']);

    //Salaire supplementaire de l'enseignant
    Route::get('/enseignant/paiement/Total_Supplementaire/{Annee}',[PaiementController::class , 'Afficher_Salaire_Sup']);

    //Salaire vacataire de l'enseignant
    Route::get('/enseignant/paiement/Total_Vacataire/{Annee}',[PaiementController::class , 'Afficher_Salaire_Vacataire']);

    


    // Route pour valider une intervention par le président
    Route::put('/president/interventions/valider/{id}', [InterventionController::class, 'Valider_By_President']);

    // Route pour invalider une intervention par le président
    Route::put('/president/interventions/non_valider/{id}', [InterventionController::class, 'Non_Valider_By_President']);

    Route::get('/president/enseignants', [EnseignantController::class, 'AffichageAll_President']);
    Route::get('/president/enseignants/{id_etablissement}', [EnseignantController::class, 'AffichagebyEtablissement_President']);
    Route::get('/president/interventions', [InterventionController::class, 'getAllInterventions_By_President']);
    Route::get('/president/interventions/etablissement/{id_etablissement}', [InterventionController::class, 'getInterventionsByEtablissement_By_President']);
    Route::get('/president/interventions/enseignant/{id_professeur}', [InterventionController::class, 'getInterventionsByenseignant_By_President']);
    Route::get('/president/interventions/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_President']);
    Route::put('/president/interventions/valider/{id}', [InterventionController::class, 'Valider_By_President']);
    Route::put('/president/interventions/non_valider/{id}', [InterventionController::class, 'Non_Valider_By_President']);


    
    Route::post('/interventions/directeur', [InterventionController::class, 'getAllInterventions_By_Directeur']);
    Route::post('/interventions/directeur/professeur/{id_professeur}', [InterventionController::class, 'getInterventionsByProfesseur_By_Directeur']);
    Route::post('/interventions/directeur/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_Directeur']);
    // Route pour valider une intervention par le directeur
    Route::put('/directeur/interventions/valider/{id}', [InterventionController::class, 'Valider_By_Directeur']);
    // Route pour invalider une intervention par le directeur
    Route::put('/directeur/interventions/non_valider/{id}', [InterventionController::class, 'Non_Valider_By_Directeur']);
    // Ajoutez d'autres routes nécessitant le rôle 'role_president' ici...



    Route::delete('/interventions/{id}', [InterventionController::class, 'destroy']);
    Route::post('/interventions/create', [InterventionController::class, 'store']);
    Route::post('/enseignants/create', [EnseignantController::class, 'store']);
    Route::get('/enseignants/{id}', [EnseignantController::class, 'show']);
    Route::delete('/enseignants/{id}', [EnseignantController::class, 'destroy']);
    Route::put('/interventions/edit/{id}', [InterventionController::class, 'update']);
    Route::get('/directeur/interventions', [InterventionController::class, 'getAllInterventions_By_Administrateur']);
    Route::get('/directeur/interventions/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_Administrateur']);
    Route::get('/directeur/interventions/semestre/{anneeUniversitaire}/{semestre}', [InterventionController::class, 'getInterventionsBySemestre_By_Administrateur']);




    Route::get('/administrateur_etb/interventions/enseignant/{id_professeur}', [InterventionController::class, 'getInterventionsByProfesseur_By_Administrateur']);
    Route::get('administrateur/interventions', [InterventionController::class, 'getAllInterventions_By_Administrateur']);
    Route::get('administrateur/interventions/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_Administrateur']);
    Route::get('administrateur/interventions/semestre/{anneeUniversitaire}/{semestre}', [InterventionController::class, 'getInterventionsBySemestre_By_Administrateur']);




    Route::get('enseignant/interventions/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_Enseignant']);
    Route::get('enseignant/interventions', [InterventionController::class, 'getAllInterventions_By_Enseignant']);
    Route::get('enseignant/interventions/etablissement/{id_etablissement}', [InterventionController::class, 'getInterventionsByEtablissement_By_Enseignant']);
});