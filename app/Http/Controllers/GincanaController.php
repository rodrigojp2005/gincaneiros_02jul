<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // Salvar no banco
        $gincana = Gincana::create($validated);

        // Redirecionar para a lista de gincanas criadas
        return redirect()->route('gincana.minhas')->with('success', 'Gincana criada com sucesso!');
    }

    public function index()
    {
        $gincanas = Gincana::all();
        return view('gincana.index', compact('gincanas'));
    }

    /**
     * Lista as gincanas criadas pelo usuário (simulado por enquanto)
     */
    public function minhasGincanas()
    {
        // Por enquanto, mostra todas as gincanas
        // Em uma implementação real, filtraria por user_id
        $gincanas = Gincana::orderBy('created_at', 'desc')->get();
        return view('gincana.minhas', compact('gincanas'));
    }

    /**
     * Mostra o formulário de edição de uma gincana
     */
    public function edit($id)
    {
        $gincana = Gincana::findOrFail($id);
        return view('gincana.editar', compact('gincana'));
    }

    /**
     * Atualiza uma gincana existente
     */
    public function update(Request $request, $id)
    {
        $gincana = Gincana::findOrFail($id);

        // Validação dos dados
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'duracao' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'contexto' => 'required|string|max:255',
            'privacidade' => 'required|in:publica,privada',
        ]);

        // Atualizar no banco
        $gincana->update($validated);

        return redirect()->route('gincana.minhas')->with('success', 'Gincana atualizada com sucesso!');
    }

    /**
     * Remove uma gincana
     */
    public function destroy($id)
    {
        $gincana = Gincana::findOrFail($id);
        $gincana->delete();

        return redirect()->route('gincana.minhas')->with('success', 'Gincana excluída com sucesso!');
    }

    /**
     * Lista as gincanas que o usuário participou (placeholder)
     */
    public function gincanasParticipadas()
    {
        // Placeholder - em uma implementação real, haveria uma tabela de participações
        $gincanas = collect(); // Lista vazia por enquanto
        return view('gincana.participadas', compact('gincanas'));
    }

    /**
     * Retorna uma nova gincana aleatória do banco de dados
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

    /**
     * Método mantido para compatibilidade
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

