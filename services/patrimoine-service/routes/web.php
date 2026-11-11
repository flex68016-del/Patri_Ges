<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\BienController;
use App\Http\Controllers\Api\AffectationController;

Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});

Route::apiResource('services', ServiceController::class);
Route::apiResource('categories', CategorieController::class);
Route::apiResource('biens', BienController::class);
Route::post('/biens/{id}/affecter', [BienController::class, 'affecter']);
Route::post('/biens/{id}/reformer', [BienController::class, 'reformer']);
Route::post('/biens/{id}/ceder', [BienController::class, 'ceder']);
Route::get('/biens/{id}/historique', [BienController::class, 'historique']);
Route::apiResource('affectations', AffectationController::class);
