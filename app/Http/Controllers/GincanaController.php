<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GincanaController extends Controller
{
    public function criar()
    {
        return view('gincana.criar');
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'duracao' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'contexto' => 'required|string|max:255',
            'privacidade' => 'required|in:publica,privada',
        ]);

        // Salvar no banco (você precisa criar o model Gincana)
        $gincana = \App\Models\Gincana::create($validated);

        // Redirecionar ou mostrar mensagem de sucesso
        return redirect()->route('gincana.criar')->with('success', 'Gincana criada com sucesso!');
    }

    public function index()
    {
        $gincanas = \App\Models\Gincana::all();
        return view('gincana.index', compact('gincanas'));
    }
}

