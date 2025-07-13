<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//add the Gincana model
use App\Models\Gincana;


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

         try {
            // Busca apenas gincanas públicas do banco de dados
            $gincana = Gincana::where('privacidade', 'publica')
                              ->inRandomOrder()
                              ->first();

            if (!$gincana) {
                // Se não houver gincanas no banco, retorna coordenadas padrão
                return response()->json([
                    'lat' => -23.55052,
                    'lng' => -46.633308,
                    'nome' => 'Gincana Padrão',
                    'contexto' => 'Nenhuma gincana encontrada no banco de dados.'
                ]);
            }

            // Retorna os dados da gincana encontrada
            return response()->json([
                'lat' => (float) $gincana->latitude,
                'lng' => (float) $gincana->longitude,
                'nome' => $gincana->nome,
                'contexto' => $gincana->contexto,
                'id' => $gincana->id
            ]);

        } catch (\Exception $e) {
            // Em caso de erro, retorna coordenadas padrão
            return response()->json([
                'lat' => -23.55052,
                'lng' => -46.633308,
                'nome' => 'Gincana Padrão',
                'contexto' => 'Erro ao carregar gincana do banco de dados.',
                'error' => $e->getMessage()
            ]);
        }
    }

    //     $newLocation = $randomLocations[array_rand($randomLocations)];

    //     return response()->json($newLocation);
    // }

    /**
     * Método mantido para compatibilidade com a implementação anterior
     * Agora redireciona para newGincana()
     */
    public function skipGincana()
    {
        return $this->newGincana();
    }

    /**
     * Retorna uma gincana específica por ID
     */
    public function getGincana($id)
    {
        try {
            $gincana = Gincana::where('id', $id)
                              ->where('privacidade', 'publica')
                              ->first();

            if (!$gincana) {
                return response()->json(['error' => 'Gincana não encontrada'], 404);
            }

            return response()->json([
                'lat' => (float) $gincana->latitude,
                'lng' => (float) $gincana->longitude,
                'nome' => $gincana->nome,
                'contexto' => $gincana->contexto,
                'id' => $gincana->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar gincana'], 500);
        }
    }

    /**
     * Retorna todas as gincanas públicas para listagem
     */
    public function getPublicGincanas()
    {
        try {
            $gincanas = Gincana::where('privacidade', 'publica')
                               ->select('id', 'nome', 'contexto', 'latitude', 'longitude', 'created_at')
                               ->orderBy('created_at', 'desc')
                               ->get();

            return response()->json($gincanas);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar gincanas'], 500);
        }
    }
}



