<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnomalieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventaireController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('authenticated')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/biens', [BienController::class, 'index'])->name('biens.index');
    Route::middleware('role:gestionnaire,administrateur')->group(function () {
        Route::get('/biens/create', [BienController::class, 'create'])->name('biens.create');
        Route::post('/biens', [BienController::class, 'store'])->name('biens.store');
        Route::get('/biens/{id}/edit', [BienController::class, 'edit'])->name('biens.edit');
        Route::post('/biens/{id}', [BienController::class, 'update'])->name('biens.update');
        Route::post('/biens/{id}/affecter', [BienController::class, 'affecter'])->name('biens.affecter');
    });
    Route::middleware('admin')->group(function () {
        Route::post('/biens/{id}/reformer', [BienController::class, 'reformer'])->name('biens.reformer');
        Route::post('/biens/{id}/ceder', [BienController::class, 'ceder'])->name('biens.ceder');
    });
    Route::get('/biens/{id}', [BienController::class, 'show'])->name('biens.show');

    Route::get('/maintenances', [MaintenanceController::class, 'index'])->name('maintenances.index');
    Route::middleware('role:gestionnaire,administrateur')->group(function () {
        Route::get('/maintenances/create', [MaintenanceController::class, 'create'])->name('maintenances.create');
        Route::post('/maintenances', [MaintenanceController::class, 'store'])->name('maintenances.store');
        Route::get('/maintenances/{id}/cloturer', [MaintenanceController::class, 'cloturerForm'])->name('maintenances.cloturer');
        Route::post('/maintenances/{id}/cloturer', [MaintenanceController::class, 'cloturer'])->name('maintenances.cloturer.store');
    });

    Route::get('/inventaires', [InventaireController::class, 'index'])->name('inventaires.index');
    Route::get('/inventaires/{id}/constats', [InventaireController::class, 'constats'])->name('inventaires.constats');
    Route::get('/inventaires/{id}/rapport', [InventaireController::class, 'rapport'])->name('inventaires.rapport');
    Route::middleware('role:gestionnaire,administrateur,inspecteur')->group(function () {
        Route::post('/inventaires/{id}/lignes', [InventaireController::class, 'storeLigne'])->name('inventaires.lignes.store');
    });
    Route::middleware('role:gestionnaire,administrateur')->group(function () {
        Route::get('/inventaires/create', [InventaireController::class, 'create'])->name('inventaires.create');
        Route::post('/inventaires', [InventaireController::class, 'store'])->name('inventaires.store');
        Route::post('/inventaires/{id}/cloturer', [InventaireController::class, 'cloturer'])->name('inventaires.cloturer');
    });

    Route::get('/anomalies', [AnomalieController::class, 'index'])->name('anomalies.index');
    Route::middleware('role:gestionnaire,administrateur,inspecteur')->group(function () {
        Route::get('/anomalies/create', [AnomalieController::class, 'create'])->name('anomalies.create');
        Route::post('/anomalies', [AnomalieController::class, 'store'])->name('anomalies.store');
        Route::post('/anomalies/{id}/resoudre', [AnomalieController::class, 'resoudre'])->name('anomalies.resoudre');
    });

    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'userCreate'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'userStore'])->name('admin.users.store');

        Route::get('/admin/references', [AdminController::class, 'references'])->name('admin.references');
        Route::get('/admin/references/services/create', [AdminController::class, 'serviceCreate'])->name('admin.services.create');
        Route::post('/admin/references/services', [AdminController::class, 'serviceStore'])->name('admin.services.store');
        Route::get('/admin/references/categories/create', [AdminController::class, 'categoryCreate'])->name('admin.categories.create');
        Route::post('/admin/references/categories', [AdminController::class, 'categoryStore'])->name('admin.categories.store');
    });
});
