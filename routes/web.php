<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GincanaController;

Route::get('/', [GameController::class, 'index']);
Route::get('/gincana/criar', [GincanaController::class, 'criar'])->name('gincana.criar');
Route::post('/gincana', [App\Http\Controllers\GincanaController::class, 'store'])->name('gincana.store');
Route::get('/gincana', [App\Http\Controllers\GincanaController::class, 'index'])->name('gincana.index');

// Rota para carregar uma nova gincana (tanto inicial quanto para pular)
Route::get("/gincana/new", [GincanaController::class, "newGincana"]);

// Rota mantida para compatibilidade com a implementação anterior
Route::get("/gincana/skip", [GincanaController::class, "skipGincana"]);

Route::get('/dashboard', function () {
    return view('dashboard');
});


// Route::get('/', function () {
//     return view('welcome');
// });

