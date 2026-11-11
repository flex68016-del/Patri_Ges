<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MaintenanceController;
use App\Http\Controllers\Api\CampagneInventaireController;
use App\Http\Controllers\Api\AnomalieController;

Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});

Route::apiResource('maintenances', MaintenanceController::class);
Route::post('/maintenances/{id}/cloturer', [MaintenanceController::class, 'cloturer']);

Route::apiResource('campagnes-inventaire', CampagneInventaireController::class);
Route::post('/campagnes-inventaire/{id}/cloturer', [CampagneInventaireController::class, 'cloturer']);

Route::apiResource('anomalies', AnomalieController::class);
Route::post('/anomalies/{id}/resoudre', [AnomalieController::class, 'resoudre']);
