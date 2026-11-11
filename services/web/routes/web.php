<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::get('/biens', function () {
    return view('biens.index');
})->name('biens.index')->middleware('auth');

Route::get('/maintenances', function () {
    return view('maintenances.index');
})->name('maintenances.index')->middleware('auth');

Route::get('/inventaires', function () {
    return view('inventaires.index');
})->name('inventaires.index')->middleware('auth');

Route::post('/logout', function () {
    return redirect()->route('login');
})->name('logout');
