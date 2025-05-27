<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfController;
use App\Http\Controllers\AvisProfController;
use App\Http\Controllers\DemandeController;



Route::post('/login', [AuthController::class, 'loginProfessional'])->name('login.post');
Route::post('/loginClient', [AuthController::class, 'loginClient'])->name('login.Client.post');
Route::post('/RegisterClient', [AuthController::class, 'registerClient'])->name('register.Client.post');


Route::post('/RegisterTransporteur', [AuthController::class, 'RegisterTransporteur'])->name('Register.Transporteur');
Route::post('/RegisterTransporteur2', [AuthController::class, 'RegisterTransporteur2'])->name('Register.Transporteur2');
Route::post('/RegisterTransporteur3', [AuthController::class, 'RegisterTransporteur3'])->name('Register.Transporteur3');

Route::get('/Dashboard', [ProfController::class, 'Dashboard'])->name('Prof.Dashboard');

Route::get('/AvisRecus', [ProfController::class, 'AvisRecus'])->name('Prof.AvisRecus');


Route::get('/demandes/{id}', [ProfController::class, 'show']);
Route::put('/demandes/{id}/accept', [ProfController::class, 'accept']);
Route::put('/demandes/{id}/refuse', [ProfController::class, 'refuse']);

Route::get('/Profile', [ProfController::class, 'GetProfile'])->name('Profile');
Route::post('/update-profile', [ProfController::class, 'UpdateProfile'])->name('Update-Profile');
Route::get('professionals/avis', [AvisProfController::class, 'GetAvisProfessionnal'])->name('professionals.avis');
Route::post('/service-request', [DemandeController::class, 'AddDemande'])->name('Demande.Add');
Route::get('/professionals/nearby/{demandeId}', [DemandeController::class, 'getNearby']);
Route::post('/addDemandeProf', [DemandeController::class, 'AddDemandeProf'])->name('Demande.Add.Prof');
Route::get('/profileProf/{profId}', [ProfController::class, 'getProfileProf'])->name('prof.prof');
Route::get('/DemandeForClient', [DemandeController::class, 'getDemandeClient']);
Route::get('/annuler-demande/{id}', [DemandeController::class, 'cancelDemande']);
