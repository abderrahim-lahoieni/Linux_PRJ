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










//-------Paiement---------
Route::get('/paiements', [PaiementController::class, 'index']);
Route::delete('/paiements/{id}', [PaiementController::class, 'destroy']);



//-------Grade-----------
Route::get('/grades', [GradeController::class, 'index']);
Route::get('/grades/{id}', [GradeController::class, 'show']);
Route::post('/grades/create', [GradeController::class, 'store']);
Route::post('/grades/{id}/edit', [GradeController::class, 'update']);

//--------Etablissement----------

//Affichage des etablissements
Route::get('/etablissements', [EtablissementController::class, 'index']);
//Creation d'une etablissement
Route::post('/etablissements/create', [EtablissementController::class, 'store']);
//Modification d'une etablissement
Route::post('/etablissements/{id}/edit', [EtablissementController::class, 'update']);
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

Route::post('/forgotPassword',[AuthController::class , 'forgotPassword']);
Route::post('/resetPassword',[AuthController::class , 'resetPassword']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    //Put your routes here, Just that's need from the user to be connected
    Route::post('/logout', [AuthController::class, 'logout']);


    // Route pour créer une nouvelle intervention

    Route::get('/enseignants', [EnseignantController::class, 'Profile']);
    Route::get('administrateur/enseignants', [EnseignantController::class, 'index']);
    Route::get('/directeur/enseignants', [EnseignantController::class, 'Affichage_Administrateur']);
    Route::get('/administrateur/enseignants', [EnseignantController::class, 'Affichage_Administrateur']);
    
});


Route::middleware('check.role:role_president')->group(function () {
    // Route pour valider une intervention par le président
     Route::put('/president/interventions/valider/{id}', [InterventionController::class, 'Valider_By_President']);

    // Route pour invalider une intervention par le président
     Route::put('/president/interventions/non_valider/{id}', [InterventionController::class, 'Non_Valider_By_President']);

    Route::get('/president/enseignants', [EnseignantController::class, 'AffichageAll_President']);
    Route::get('/president/enseignants/{id}', [EnseignantController::class, 'AffichagebyEtablissement_President']);  
    Route::get('/interventions', [InterventionController::class, 'getAllInterventions_By_President']);
    Route::get('/interventions/president/etablissement/{id_etablissement}', [InterventionController::class, 'getInterventionsByEtablissement_By_President']);
    Route::get('/interventions/president/enseignant/{id_professeur}', [InterventionController::class, 'getInterventionsByenseignant_By_President']);
    Route::get('/interventions/president/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_President']);


});
// Routes nécessitant le rôle 'role_directeur'
Route::middleware('check.role:role_directeur')->group(function () {
   
    Route::post('/interventions/directeur', [InterventionController::class, 'getAllInterventions_By_Directeur']);
    Route::post('/interventions/directeur/professeur/{id_professeur}', [InterventionController::class, 'getInterventionsByProfesseur_By_Directeur']);
    Route::post('/interventions/directeur/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_Directeur']);
    // Route pour valider une intervention par le directeur
    Route::put('/directeur/interventions/valider/{id}', [InterventionController::class, 'Valider_By_Directeur']);
    // Route pour invalider une intervention par le directeur
    Route::put('/directeur/interventions/non_valider/{id}', [InterventionController::class, 'Non_Valider_By_Directeur']);
    // Ajoutez d'autres routes nécessitant le rôle 'role_president' ici...
});

// Routes nécessitant le rôle 'role_admin_eta'
 Route::middleware('check.role:role_admin_eta')->group(function () {

    Route::delete('/interventions/{id}', [InterventionController::class, 'destroy']);
    Route::post('/interventions/create', [InterventionController::class, 'store']);
    Route::post('/enseignants/create', [EnseignantController::class, 'store']);
    Route::get('/enseignants/{id}', [EnseignantController::class, 'show']);
    Route::delete('/enseignants/{id}', [EnseignantController::class, 'destroy']);
    Route::put('/interventions/edit/{id}', [InterventionController::class, 'update']);
    Route::get('directeur/interventions', [InterventionController::class,'getAllInterventions_By_Administrateur']);
    Route::get('directeur/interventions/annee/{anneeUniversitaire}',  [InterventionController::class,'getInterventionsByAnnee_By_Administrateur']);
    Route::get('directeur/interventions/semestre/{anneeUniversitaire}/{semestre}', [InterventionController::class,'getInterventionsBySemestre_By_Administrateur']);

});

// Routes nécessitant le rôle 'role_admin_univ'
 Route::middleware('check.role:role_admin_univ')->group(function () {
    Route::post('/administrateurs/create', [AdministrateurController::class, 'store']);
    Route::get('/administrateurs', [AdministrateurController::class, 'index']);
Route::get('/administrateurs/{id}', [AdministrateurController::class, 'show']);
Route::delete('/administrateurs/{id}', [AdministrateurController::class, 'destroy']); 
Route::post('/register', [AuthController::class, 'register']);
Route::get('enseignant/interventions/administrateur/{id_professeur}', [InterventionController::class,'getInterventionsByProfesseur_By_Administrateur']);
    Route::get('administrateur/interventions', [InterventionController::class,'getAllInterventions_By_Administrateur']);
    Route::get('administrateur/interventions/annee/{anneeUniversitaire}',  [InterventionController::class,'getInterventionsByAnnee_By_Administrateur']);
    Route::get('administrateur/interventions/semestre/{anneeUniversitaire}/{semestre}', [InterventionController::class,'getInterventionsBySemestre_By_Administrateur']);
   

});
// Routes nécessitant le rôle 'role_enseignant'
Route::middleware('check.role:role_enseignant')->group(function () {
    Route::get('enseignant/interventions/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_Enseignant']);
    Route::get('enseignant/interventions', [InterventionController::class, 'getAllInterventions_By_Enseignant']);
    Route::get('enseignant/interventions/etablissement/{id_etablissement}', [InterventionController::class, 'getInterventionsByEtablissement_By_Enseignant']);
});
