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

//--------Intervention-------------
Route::get('/interventions', [InterventionController::class, 'getAllInterventions_By_President']);
Route::get('/interventions/president/etablissement/{id_etablissement}', [InterventionController::class, 'getInterventionsByEtablissement_By_President']);
Route::get('/interventions/president/enseignant/{id_professeur}', [InterventionController::class, 'getInterventionsByenseignant_By_President']);
Route::get('/interventions/president/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_President']);
Route::get('/interventions/enseignant', [InterventionController::class, 'getAllInterventions_By_Enseignant'])->middleware('auth:sanctum');;

Route::post('/interventions/enseignant/etablissement/{id_etablissement}', [InterventionController::class, 'getInterventionsByEtablissement_By_Enseignant']);
Route::post('/interventions/enseignant/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_Enseignant']);

Route::post('/interventions/directeur', [InterventionController::class, 'getAllInterventions_By_Directeur']);


Route::post('/interventions/directeur/professeur/{id_professeur}', [InterventionController::class, 'getInterventionsByProfesseur_By_Directeur']);

Route::post('/interventions/directeur/annee/{anneeUniversitaire}', [InterventionController::class, 'getInterventionsByAnnee_By_Directeur']);


// Route pour créer une nouvelle intervention
Route::post('/interventions', [InterventionController::class, 'store']);

// Route pour mettre à jour une intervention existante
Route::put('/interventions/{id}', [InterventionController::class, 'update']);

// Route pour valider une intervention par le directeur
Route::put('/interventions/{id}/valider/directeur', [InterventionController::class, 'Valider_By_Directeur']);

// Route pour invalider une intervention par le directeur
Route::put('/interventions/{id}/non-valider/directeur', [InterventionController::class, 'Non_Valider_By_Directeur']);

// Route pour valider une intervention par le président
Route::put('/interventions/{id}/valider/president', [InterventionController::class, 'Valider_By_President']);

// Route pour invalider une intervention par le président
Route::put('/interventions/{id}/non-valider/president', [InterventionController::class, 'Non_Valider_By_President']);

// Route pour supprimer une intervention
Route::delete('/interventions/{id}', [InterventionController::class,'destroy']);

//-------Paiement---------
Route::get('/paiements', [PaiementController::class,'index']);
Route::delete('/paiements/{id}', [PaiementController::class,'destroy']);

//------Administrateur------
Route::post('/administrateurs/create', [AdministrateurController::class,'store']);
Route::get('/administrateurs', [AdministrateurController::class,'index']);
Route::get('/administrateurs/{id}', [AdministrateurController::class,'show']);
Route::delete('/administrateurs/{id}', [AdministrateurController::class,'destroy']);

//-------Grade-----------
Route::get('/grades', [GradeController::class,'index']);
Route::get('/grades/{id}', [GradeController::class,'show']);
Route::post('/grades/create', [GradeController::class,'store']);
Route::post('/grades/{id}/edit', [GradeController::class,'update']);

//--------Etablissement----------

//Affichage des etablissements
Route::get('/etablissements', [EtablissementController::class,'index']);
//Creation d'une etablissement
Route::post('/etablissements/create', [EtablissementController::class,'store']);
//Modification d'une etablissement
Route::post('/etablissements/{id}/edit', [EtablissementController::class,'update']);
//Suppression d'une etablissement
Route::delete('/etablissements/{etablissement}', [EtablissementController::class,'delete']);
//Affichage d'une etablissement
Route::get('/etablissements/{id}', [EtablissementController::class,'show']);


//affichage d'un enseignant:
Route::post('/enseignants/create',[EnseignantController::class,'store']);
Route::get('/enseignants/{id}', [EnseignantController::class,'show']);
Route::delete('/enseignants/{id}', [EnseignantController::class,'destroy']);


//Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']],function(){
    //Put your routes here, Just that's need from the user to be connected
    Route::post('/logout', [AuthController::class, 'logout']);
});