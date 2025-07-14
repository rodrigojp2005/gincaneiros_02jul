<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GincanaController;

Route::get('/', [GameController::class, 'index']);

// Rotas para gincanas
Route::get('/gincana/criar', [GincanaController::class, 'criar'])->name('gincana.criar');
Route::post('/gincana', [GincanaController::class, 'store'])->name('gincana.store');
Route::get('/gincana', [GincanaController::class, 'index'])->name('gincana.index');

// Novas rotas para "Minhas Gincanas"
Route::get('/minhas-gincanas', [GincanaController::class, 'minhasGincanas'])->name('gincana.minhas');
Route::get('/gincana/{id}/editar', [GincanaController::class, 'edit'])->name('gincana.edit');
Route::put('/gincana/{id}', [GincanaController::class, 'update'])->name('gincana.update');
Route::delete('/gincana/{id}', [GincanaController::class, 'destroy'])->name('gincana.destroy');

// Rota para gincanas participadas
Route::get('/gincanas-participadas', [GincanaController::class, 'gincanasParticipadas'])->name('gincana.participadas');

// Rotas para carregar gincanas (API)
Route::get("/gincana/new", [GincanaController::class, "newGincana"]);
Route::get("/gincana/skip", [GincanaController::class, "skipGincana"]);
Route::get("/gincana/{id}", [GincanaController::class, "getGincana"]);
Route::get("/api/gincanas/public", [GincanaController::class, "getPublicGincanas"]);

Route::get('/dashboard', function () {
    return view('dashboard');
});


// Route::get('/', function () {
//     return view('welcome');
// });

