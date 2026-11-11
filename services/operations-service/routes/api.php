<?php

use App\Http\Controllers\Api\AnomalieController;
use App\Http\Controllers\Api\CampagneInventaireController;
use App\Http\Controllers\Api\MaintenanceController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:100,1')->group(function () {
    Route::middleware('jwt')->group(function () {
        // Maintenances
        Route::get('/maintenances', [MaintenanceController::class, 'index']);
        Route::get('/maintenances/{id}', [MaintenanceController::class, 'show']);

        Route::middleware('role:gestionnaire,administrateur')->group(function () {
            Route::post('/maintenances', [MaintenanceController::class, 'store']);
            Route::post('/maintenances/{id}/cloturer', [MaintenanceController::class, 'cloturer']);
        });

        // Campagnes d'inventaire
        Route::get('/campagnes-inventaire', [CampagneInventaireController::class, 'index']);
        Route::get('/campagnes-inventaire/{id}', [CampagneInventaireController::class, 'show']);

        Route::middleware('role:gestionnaire,administrateur')->group(function () {
            Route::post('/campagnes-inventaire', [CampagneInventaireController::class, 'store']);
            Route::post('/campagnes-inventaire/{id}/cloturer', [CampagneInventaireController::class, 'cloturer']);
        });

        // Anomalies
        Route::get('/anomalies', [AnomalieController::class, 'index']);
        Route::get('/anomalies/{id}', [AnomalieController::class, 'show']);

        Route::middleware('role:gestionnaire,administrateur,inspecteur')->group(function () {
            Route::post('/anomalies', [AnomalieController::class, 'store']);
            Route::post('/anomalies/{id}/resoudre', [AnomalieController::class, 'resoudre']);
        });
    });
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'operations-service',
        'timestamp' => now()->toIso8601String(),
    ]);
});
