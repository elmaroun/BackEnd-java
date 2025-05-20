<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'loginProfessional'])->name('login.post');
Route::post('/loginClient', [AuthController::class, 'loginClient'])->name('login.Client.post');
Route::post('/RegisterClient', [AuthController::class, 'registerClient'])->name('register.Client.post');


Route::post('/RegisterTransporteur', [AuthController::class, 'RegisterTransporteur'])->name('Register.Transporteur');
Route::post('/RegisterTransporteur2', [AuthController::class, 'RegisterTransporteur2'])->name('Register.Transporteur2');
Route::post('/RegisterTransporteur3', [AuthController::class, 'RegisterTransporteur3'])->name('Register.Transporteur3');


Route::get('/set-session', function () {
    session(['user_id' => 999]);
    return response()->json(['message' => 'Session set']);
});

Route::get('/get-session', function () {
    return response()->json(['session' => session()->all()]);
});