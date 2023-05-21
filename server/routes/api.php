<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdministrateurController;

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

//Administrateur
Route::post('/administrateurs/create', [AdministrateurController::class,'store']);
Route::get('/administrateurs', [AdministrateurController::class,'index']);
Route::get('/administrateurs/{id}', [AdministrateurController::class,'show']);
Route::delete('/administrateurs/{id}', [AdministrateurController::class,'destroy']);

//-------Grade-----------
Route::get('/grades', [GradeController::class,'index']);

//--------Etablissement----------

//Affichage des etablissements
Route::get('/etablissements', [EtablissementController::class,'index']);
//Creation d'une etablissement
Route::post('/etablissements/create', [EtablissementController::class,'store']);
//Modification d'une etablissement
Route::post('/etablissements/edit/{id}', [EtablissementController::class,'update']);
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