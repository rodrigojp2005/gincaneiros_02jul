<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GincanaController;

Route::get('/', [GameController::class, 'index']);
Route::get('/gincana/criar', [GincanaController::class, 'criar'])->name('gincana.criar');

Route::get('/dashboard', function () {
    return view('dashboard');
});


// Route::get('/', function () {
//     return view('welcome');
// });
