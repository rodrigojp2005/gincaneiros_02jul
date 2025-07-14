@extends('layouts.app')
@section('content')
<div id="form_container" style="max-width: 800px; margin: 60px auto 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Minhas Gincanas Criadas</h2>
        <a href="{{ route('gincana.criar') }}" style="padding: 10px 20px; background-color: #198754; color: white; text-decoration: none; border-radius: 4px;">
            + Nova Gincana
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($gincanas->count() > 0)
        <div style="display: grid; gap: 20px;">
            @foreach($gincanas as $gincana)
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: #f9f9f9;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                        <div style="flex: 1;">
                            <h3 style="margin: 0 0 10px 0; color: #333;">{{ $gincana->nome }}</h3>
                            <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">{{ $gincana->contexto }}</p>
                            <div style="display: flex; gap: 20px; font-size: 12px; color: #888;">
                                <span><strong>Duração:</strong> {{ $gincana->duracao }}h</span>
                                <span><strong>Privacidade:</strong> {{ ucfirst($gincana->privacidade) }}</span>
                                <span><strong>Criada em:</strong> {{ $gincana->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; margin-left: 20px;">
                            <a href="{{ route('gincana.edit', $gincana->id) }}" 
                               style="padding: 8px 16px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('gincana.destroy', $gincana->id) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta gincana?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="padding: 8px 16px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 15px;">
                        <div style="display: flex; gap: 20px; font-size: 12px; color: #666;">
                            <span><strong>Coordenadas:</strong> {{ number_format($gincana->latitude, 6) }}, {{ number_format($gincana->longitude, 6) }}</span>
                            @if($gincana->privacidade === 'publica')
                                <span style="color: #198754;"><strong>✓ Visível publicamente</strong></span>
                            @else
                                <span style="color: #ffc107;"><strong>🔒 Apenas com link</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <h3>Você ainda não criou nenhuma gincana</h3>
            <p>Que tal criar sua primeira gincana e desafiar seus amigos?</p>
            <a href="{{ route('gincana.criar') }}" style="padding: 12px 24px; background-color: #198754; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; display: inline-block;">
                Criar Primeira Gincana
            </a>
        </div>
    @endif
</div>

<style>
    @media (max-width: 600px) {
        #form_container {
            padding: 10px;
            margin-top: 20px !important;
        }
        
        .gincana-card {
            padding: 15px !important;
        }
        
        .gincana-actions {
            flex-direction: column !important;
            gap: 8px !important;
        }
        
        .gincana-actions a,
        .gincana-actions button {
            width: 100% !important;
            text-align: center !important;
        }
    }
</style>
@endsection

