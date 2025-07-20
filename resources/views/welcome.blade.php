@extends('layouts.app')
@section('content')
    <!-- Conteúdo Central (Street View) -->
    <button id="openMapBtn" class="map-action-btn">Abrir Mapa</button>

    <!-- Informações da Gincana -->
    <div id="gincanaInfo"></div>

    <!-- Sidebar (Mapa para palpite) -->
    <div id="mapSidebar">
        <div class="sidebar-header">
            <button id="closeMapBtn">✕ Fechar</button>
        </div>
        <div id="map"></div>
        <button id="confirmBtn">Confirmar Local</button>
    </div>

    <div id="street-view"></div>
@endsection
