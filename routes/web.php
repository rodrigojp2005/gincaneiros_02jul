<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', [GameController::class, 'index']);

// Route::get('/', function () {
//     return view('welcome');
// });
