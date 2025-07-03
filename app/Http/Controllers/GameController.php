<?php

// app/Http/Controllers/GameController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return view('welcome'); // ou 'game.index', dependendo do seu blade
    }
}
// app/Http/Controllers/GameController.php