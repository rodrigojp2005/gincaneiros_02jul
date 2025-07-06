@extends('layouts.app')
@section('content')
    <!-- Conteúdo Central (Street View) -->
   
        <!-- Botão "Ver Mapa" -->
        <button id="openMapBtn" class="map-action-btn">Abrir Mapa</button>
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

<script>
    // Função global para o Google Maps
    function initMap() {
        const panorama = new google.maps.StreetViewPanorama(
            document.getElementById("street-view"),
            {
                position: { lat: -23.55052, lng: -46.633308 },
                pov: { heading: 300, pitch: 0 }, // Experimente 0, 90, 180, 270
                zoom: 1,
                disableDefaultUI: true
            }
        );

        // Adiciona a figurinha como marcador no panorama
        const marker = new google.maps.Marker({
            position: { lat: -23.55052, lng: -46.633308 },
            map: panorama,
            icon: {
                url: "https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExeTRweGJoMHk1eG5nb2tyOHMyMHp1ZGlpYTFoZDZ6Ym9zZ3ZkYXB6MSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9cw/bvQHYGOF8UOXqXSFir/giphy.gif",
                scaledSize: new google.maps.Size(60, 80) // ajuste o tamanho conforme necessário
            },
            // O marker aparece só no panorama, não no mapa tradicional
            visible: true
        });

        marker.addListener('click', function() {
            Swal.fire({
                title: 'Onde estou ...',
                text: 'Procure no mapa e ajude a me encontrarem neste local desconhecido, buá, buá!',
                icon: 'question',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
            });
        });
    }
    window.initMap = initMap;

    // Aguarda o DOM estar pronto para o restante do código
    document.addEventListener('DOMContentLoaded', function() {
        // Variáveis globais
        let selectedLatLng = null;
        let map;
        let marker;
        
        // Local verdadeiro do personagem
        const trueLocation = {
            lat: -23.55052,
            lng: -46.633308
        };

        let attempts = 0;
        let maxAttempts = 5;
        let score = 1000;

        // Funções principais
        function toggleMenu() {
            const mobileMenu = document.getElementById("mobileMenu");
            if (mobileMenu) {
                mobileMenu.classList.toggle("active");
            }
        }
        
        
        function toggleSidebar(open = true) {
            const sidebar = document.getElementById("mapSidebar");
            if (sidebar) {
                if (open) {
                    sidebar.classList.add("open");
                } else {
                    sidebar.classList.remove("open");
                }
            }
        }

        function initMapSelector() {
            if (map) return; // já foi inicializado

            const mapElement = document.getElementById("map");
            if (!mapElement) return;

            map = new google.maps.Map(mapElement, {
                center: { lat: -23.55052, lng: -46.633308 },
                zoom: 4,
            });

            map.addListener("click", function (e) {
                selectedLatLng = {
                    lat: e.latLng.lat(),
                    lng: e.latLng.lng()
                };

                if (marker) marker.setMap(null);

                marker = new google.maps.Marker({
                    position: selectedLatLng,
                    map: map,
                    title: "Seu palpite"
                });
            });
        }

        function getDirectionHint(from, to) {
            const latDiff = to.lat - from.lat;
            const lngDiff = to.lng - from.lng;

            const vertical = latDiff > 0 ? 'norte' : 'sul';
            const horizontal = lngDiff > 0 ? 'leste' : 'oeste';

            if (Math.abs(latDiff) > Math.abs(lngDiff)) {
                return vertical;
            } else {
                return horizontal;
            }
        }

        function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            const R = 6371; // Raio da Terra em km
            const dLat = deg2rad(lat2 - lat1);
            const dLon = deg2rad(lon2 - lon1);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);

            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c; // Distância em km
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180);
        }

        function googleLogin() {
            const provider = new firebase.auth.GoogleAuthProvider();
            firebase.auth().signInWithPopup(provider)
                .then((result) => {
                    const user = result.user;
                    localStorage.setItem('g_user', JSON.stringify(user));
                    window.location.href = "/dashboard";
                })
                .catch((error) => {
                    console.error(error);
                    Swal.fire('Erro ao logar', error.message, 'error');
                });
        }

        // Event Listeners - Verificando se os elementos existem
        const openMapBtn = document.getElementById("openMapBtn");
        if (openMapBtn) {
            openMapBtn.addEventListener("click", () => {
                toggleSidebar(true);
                initMapSelector();
            });
        }

        const confirmBtn = document.getElementById("confirmBtn");
        if (confirmBtn) {
            confirmBtn.addEventListener("click", () => {
                if (!selectedLatLng) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Escolha um local',
                        text: 'Clique no mapa para dar seu palpite.'
                    });
                    return;
                }

                const distance = getDistanceFromLatLonInKm(
                    selectedLatLng.lat,
                    selectedLatLng.lng,
                    trueLocation.lat,
                    trueLocation.lng
                );

                attempts++;

                if (distance <= 1) {
                    Swal.fire({
                        icon: 'success',
                        title: '🎉 Você acertou!',
                        text: `Distância: ${distance.toFixed(2)} km. Parabéns!`,
                        confirmButtonText: 'Fechar'
                    });
                } else if (attempts < maxAttempts) {
                    score -= 200;
                    let directionHint = getDirectionHint(selectedLatLng, trueLocation);

                    Swal.fire({
                        icon: 'info',
                        title: `Você está a ${distance.toFixed(1)} km do local`,
                        html: `<p>Dica: tente mais ao <strong>${directionHint}</strong>.</p><p>Chances restantes: ${maxAttempts - attempts}</p>`,
                        confirmButtonText: 'Tentar de novo'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Não foi dessa vez!',
                        html: `<p>Você errou todas as tentativas.</p>
                               <p>O local correto era: ${trueLocation.lat.toFixed(5)}, ${trueLocation.lng.toFixed(5)}</p>`,
                        showCancelButton: true,
                        confirmButtonText: 'Fazer login',
                        cancelButtonText: 'Fechar'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            location.reload();
                        }
                    });
                }
            });
        }

        const closeMapBtn = document.getElementById("closeMapBtn");
        if (closeMapBtn) {
            closeMapBtn.addEventListener("click", () => {
                toggleSidebar(false);
            });
        }

        // Tornar funções globais para uso em outros lugares
        window.toggleMenu = toggleMenu;
        window.showHowToPlay = showHowToPlay;
        window.showAbout = showAbout;

        // Chame initMap manualmente após o DOM estar pronto e o script do Google Maps carregado
        if (typeof google !== "undefined" && google.maps) {
            initMap();
        } else {
            // Se o Google Maps ainda não carregou, aguarde até carregar
            window.initMap = initMap;
        }
    });
</script>
