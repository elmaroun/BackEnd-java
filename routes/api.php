<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'loginProfessional'])->name('login.post');
Route::post('/loginClient', [AuthController::class, 'loginClient'])->name('login.Client.post');
Route::post('/RegisterClient', [AuthController::class, 'registerClient'])->name('register.Client.post');


Route::post('/RegisterTransporteur', [AuthController::class, 'RegisterTransporteur'])->name('Register.Transporteur');