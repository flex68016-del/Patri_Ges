<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\BienController;
use App\Http\Controllers\Api\AffectationController;

Route::middleware('throttle:100,1')->group(function () {
    Route::middleware('jwt')->group(function () {
        // Services (lecture seule pour tous)
        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/{id}', [ServiceController::class, 'show']);

        // Catégories (lecture seule pour tous)
        Route::get('/categories', [CategorieController::class, 'index']);
        Route::get('/categories/{id}', [CategorieController::class, 'show']);

        // Biens (lecture pour tous, écriture pour gestionnaire et administrateur)
        Route::get('/biens', [BienController::class, 'index']);
        Route::get('/biens/{id}', [BienController::class, 'show']);
        Route::get('/biens/{id}/historique', [BienController::class, 'historique']);

        Route::middleware('role:gestionnaire,administrateur')->group(function () {
            Route::post('/biens', [BienController::class, 'store']);
            Route::put('/biens/{id}', [BienController::class, 'update']);
            Route::post('/biens/{id}/affecter', [BienController::class, 'affecter']);
        });

        Route::middleware('role:administrateur')->group(function () {
            Route::post('/biens/{id}/reformer', [BienController::class, 'reformer']);
            Route::post('/biens/{id}/ceder', [BienController::class, 'ceder']);
        });

        // Affectations
        Route::get('/affectations', [AffectationController::class, 'index']);
        Route::get('/affectations/{id}', [AffectationController::class, 'show']);
    });
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'patrimoine-service',
        'timestamp' => now()->toIso8601String(),
    ]);
});
