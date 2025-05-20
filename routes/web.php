<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/RegisterTransporteur', [AuthController::class, 'RegisterTransporteur'])->name('Register.Transporteur');
Route::post('/RegisterTransporteur2', [AuthController::class, 'RegisterTransporteur2'])->name('Register.Transporteur2');


