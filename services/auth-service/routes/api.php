<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Login : limite a 5 tentatives par minute et par IP pour freiner
// les attaques par force brute sur les mots de passe.
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes authentifiees : le middleware jwt verifie la signature RS256,
// l'expiration et le statut actif de l'utilisateur.
Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // /api/me est utilise par les autres microservices pour revalider
    // l'utilisateur et recuperer son role a jour.
    Route::get('/me', [AuthController::class, 'me']);

    // Gestion des comptes : administrateur uniquement.
    Route::middleware('role:administrateur')->group(function () {
        Route::get('/users', [AuthController::class, 'users']);
        Route::post('/users', [AuthController::class, 'storeUser']);
    });
});

// Healthcheck public : utilise par Docker/SnapDeploy pour verifier
// que le service repond avant de router le trafic.
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'auth-service',
        'timestamp' => now()->toIso8601String(),
    ]);
});
