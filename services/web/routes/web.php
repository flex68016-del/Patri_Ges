<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/biens', function () {
    return view('biens.index');
})->name('biens.index');

Route::get('/biens/{id}', function ($id) {
    return view('biens.show', ['id' => $id]);
})->name('biens.show');

Route::get('/biens/create', function () {
    return view('biens.create');
})->name('biens.create');

Route::get('/biens/{id}/edit', function ($id) {
    return view('biens.edit', ['id' => $id]);
})->name('biens.edit');

Route::get('/maintenances', function () {
    return view('maintenances.index');
})->name('maintenances.index');

Route::get('/maintenances/create', function () {
    return view('maintenances.create');
})->name('maintenances.create');

Route::get('/inventaires', function () {
    return view('inventaires.index');
})->name('inventaires.index');

Route::get('/inventaires/create', function () {
    return view('inventaires.create');
})->name('inventaires.create');
