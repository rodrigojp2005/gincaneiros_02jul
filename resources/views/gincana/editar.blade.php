@extends('layouts.app')
@section('content')
<div id="form_container" style="max-width: 600px; margin: 60px auto 0 auto; padding: 20px;">
    <h2 style="margin-bottom: 18px;">Editar Gincana</h2>
    <form id="form-editar-gincana" method="POST" action="{{ route('gincana.update', $gincana->id) }}">
        @csrf
        @method('PUT')

        <!-- Nome da Gincana -->
        <div style="margin-bottom: 16px;">
            <label for="nome" style="display: block; font-weight: bold; margin-bottom: 6px;">Nome da Gincana</label>
            <input type="text" id="nome" name="nome" value="{{ old('nome', $gincana->nome) }}" placeholder="Ex.: Onde estudei na infância, onde quero viajar ou encontre meu sonho..." required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <!-- Duração -->
        <div style="margin-bottom: 16px;">
            <label for="duracao" style="display: block; font-weight: bold; margin-bottom: 6px;">Duração</label>
            <select id="duracao" name="duracao" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="24" {{ old('duracao', $gincana->duracao) == 24 ? 'selected' : '' }}>24 horas</option>
                <option value="48" {{ old('duracao', $gincana->duracao) == 48 ? 'selected' : '' }}>48 horas</option>
                <option value="72" {{ old('duracao', $gincana->duracao) == 72 ? 'selected' : '' }}>72 horas</option>
            </select>
        </div>

        <!-- Mapa do Google Maps -->
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Escolha o local do personagem perdido</label>
            <div id="map" style="height: 300px; width: 100%; border: 1px solid #ccc; border-radius: 4px;"></div>
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $gincana->latitude) }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $gincana->longitude) }}">
        </div>

        <!-- Campo de Cidade -->
        <div style="margin-bottom: 16px;">
            <label for="cidade" style="display: block; font-weight: bold; margin-bottom: 6px;">Cidade / Localização</label>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="cidade" placeholder="Digite a cidade ou endereço que vc quer que descubram." style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <button type="button" onclick="buscarCidade()" style="padding: 8px 12px; background-color: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Buscar
                </button>
            </div>
        </div>

        <!-- Texto de Contextualização -->
        <div style="margin-bottom: 16px;">
            <label for="contexto" style="display: block; font-weight: bold; margin-bottom: 6px;">Texto de Contextualização (fala do personagem)</label>
            <textarea id="contexto" name="contexto" rows="3" maxlength="255" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">{{ old('contexto', $gincana->contexto) }}</textarea>
        </div>

        <!-- Privacidade -->
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Privacidade</label>
            <div style="margin-bottom: 8px;">
                <input type="radio" name="privacidade" id="publica" value="publica" {{ old('privacidade', $gincana->privacidade) == 'publica' ? 'checked' : '' }}>
                <label for="publica">Pública (qualquer um pode jogar)</label>
            </div>
            <div>
                <input type="radio" name="privacidade" id="privada" value="privada" {{ old('privacidade', $gincana->privacidade) == 'privada' ? 'checked' : '' }}>
                <label for="privada">Privada (apenas quem tem o link pode jogar)</label>
            </div>
        </div>

        <!-- Botões -->
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <button type="submit" style="padding: 10px 20px; background-color: #198754; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Atualizar Gincana
            </button>
            <a href="{{ route('gincana.minhas') }}" style="padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection

<!-- Google Maps Script -->
<script>
    let map, marker, geocoder;

    function initMap() {
        // Usa as coordenadas da gincana existente
        const currentLocation = { 
            lat: {{ $gincana->latitude }}, 
            lng: {{ $gincana->longitude }} 
        };
        
        map = new google.maps.Map(document.getElementById('map'), {
            center: currentLocation,
            zoom: 14
        });

        geocoder = new google.maps.Geocoder();
       
        // Cria o marcador no mapa na posição atual
        marker = new google.maps.Marker({
            position: currentLocation,
            map: map,
            draggable: true
        });

        // Atualiza campos hidden ao mover o marcador
        function updateLatLngFields(position) {
            document.getElementById('latitude').value = position.lat();
            document.getElementById('longitude').value = position.lng();
        }

        marker.addListener('dragend', function() {
            updateLatLngFields(marker.getPosition());
        });

        map.addListener('click', function(event) {
            marker.setPosition(event.latLng);
            updateLatLngFields(event.latLng);
        });
    }

    function buscarCidade() {
        const endereco = document.getElementById('cidade').value;
        if (!endereco) return;

        geocoder.geocode({ address: endereco }, function (results, status) {
            if (status === 'OK') {
                const location = results[0].geometry.location;
                map.setCenter(location);
                map.setZoom(14);
                marker.setPosition(location);
                document.getElementById('latitude').value = location.lat();
                document.getElementById('longitude').value = location.lng();
            } else {
                alert('Local não encontrado: ' + status);
            }
        });
    }

    window.onload = initMap;
</script>

