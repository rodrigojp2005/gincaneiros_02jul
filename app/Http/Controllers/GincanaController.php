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

    /**
     * Retorna uma nova gincana (coordenadas aleatórias)
     * Esta função é chamada tanto para carregar a gincana inicial
     * quanto para pular para uma nova gincana
     */
    public function newGincana()
    {
        // Em um cenário real, você buscaria novas coordenadas de um banco de dados
        // ou de uma API de gincanas. Para este exemplo, vamos usar coordenadas aleatórias.
        $randomLocations = [
            ["lat" => -22.9068, "lng" => -43.1729], // Rio de Janeiro, Brasil
            ["lat" => 34.0522, "lng" => -118.2437], // Los Angeles, EUA
            ["lat" => 48.8566, "lng" => 2.3522],   // Paris, França
            ["lat" => 35.6895, "lng" => 139.6917], // Tóquio, Japão
            ["lat" => -33.8688, "lng" => 151.2093], // Sydney, Austrália
            ["lat" => 51.5074, "lng" => -0.1278],   // Londres, Reino Unido
            ["lat" => 40.7128, "lng" => -74.0060],  // Nova York, EUA
            ["lat" => -23.5505, "lng" => -46.6333], // São Paulo, Brasil
            ["lat" => 55.7558, "lng" => 37.6176],   // Moscou, Rússia
            ["lat" => 39.9042, "lng" => 116.4074]   // Pequim, China
        ];

        $newLocation = $randomLocations[array_rand($randomLocations)];

        return response()->json($newLocation);
    }

    /**
     * Método mantido para compatibilidade com a implementação anterior
     * Agora redireciona para newGincana()
     */
    public function skipGincana()
    {
        return $this->newGincana();
    }
}

