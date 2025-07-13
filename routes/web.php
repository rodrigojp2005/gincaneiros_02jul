<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GincanaController;

Route::get('/', [GameController::class, 'index']);
Route::get('/gincana/criar', [GincanaController::class, 'criar'])->name('gincana.criar');
Route::post('/gincana', [GincanaController::class, 'store'])->name('gincana.store');
Route::get('/gincana', [GincanaController::class, 'index'])->name('gincana.index');

// Rota para carregar uma nova gincana aleatória do banco de dados
Route::get("/gincana/new", [GincanaController::class, "newGincana"]);

// Rota mantida para compatibilidade com a implementação anterior
Route::get("/gincana/skip", [GincanaController::class, "skipGincana"]);

// Rota para buscar uma gincana específica por ID
Route::get("/gincana/{id}", [GincanaController::class, "getGincana"]);

// Rota para listar todas as gincanas públicas
Route::get("/api/gincanas/public", [GincanaController::class, "getPublicGincanas"]);

Route::get('/dashboard', function () {
    return view('dashboard');
});


// Route::get('/', function () {
//     return view('welcome');
// });

