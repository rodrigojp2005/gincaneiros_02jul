@extends('layouts.app')
@section('content')
    <!-- Conteúdo Central -->
       <!-- Botão "Ver Mapa" -->
        <button id="openMapBtn" class="map-action-btn">Abrir Mapa</button>
        <button id="skipGincanaBtn" class="map-action-btn">Pular Gincana</button>
        
        <!-- Informações da Gincana -->
        <div id="gincanaInfo" style="position: fixed; top: 80px; left: 20px; background: rgba(255,255,255,0.9); padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000; max-width: 300px; display: none;">
            <h4 id="gincanaName" style="margin: 0 0 10px 0; color: #333;"></h4>
            <p id="gincanaContext" style="margin: 0; color: #666; font-size: 14px;"></p>
        </div>
        
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
    // Variáveis globais
    let selectedLatLng = null;
    let map;
    let marker;
    let panorama;
    let characterMarker;
    let currentGincana = null;
    
    // Local verdadeiro do personagem (será carregado dinamicamente)
    let trueLocation = {
        lat: null,
        lng: null
    };

    let attempts = 0;
    let maxAttempts = 5;
    let score = 1000;

    // Função global para controlar a sidebar
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

    // Função para mostrar informações da gincana
    function showGincanaInfo(gincana) {
        const infoDiv = document.getElementById("gincanaInfo");
        const nameElement = document.getElementById("gincanaName");
        const contextElement = document.getElementById("gincanaContext");
        
        if (infoDiv && nameElement && contextElement) {
            nameElement.textContent = gincana.nome || 'Gincana';
            contextElement.textContent = gincana.contexto || 'Descubra onde estou!';
            infoDiv.style.display = 'block';
            
            // Auto-hide após 5 segundos
            setTimeout(() => {
                infoDiv.style.display = 'none';
            }, 5000);
        }
    }

    // Função para carregar uma nova gincana
    function loadNewGincana() {
        fetch("/gincana/new")
            .then(response => response.json())
            .then(data => {
                if (data.lat && data.lng) {
                    currentGincana = data;
                    updateGameLocation(data.lat, data.lng);
                    showGincanaInfo(data);
                }
            })
            .catch(error => {
                console.error("Erro ao carregar nova gincana:", error);
                Swal.fire("Erro", "Não foi possível carregar uma nova gincana.", "error");
            });
    }

    // Função para atualizar a localização do jogo
    function updateGameLocation(lat, lng) {
        // Atualiza a localização do Street View
        panorama = new google.maps.StreetViewPanorama(
            document.getElementById("street-view"),
            {
                position: { lat: lat, lng: lng },
                pov: { heading: 300, pitch: 0 },
                zoom: 1,
                disableDefaultUI: true
            }
        );

        // Remove o marcador anterior se existir
        if (characterMarker) {
            characterMarker.setMap(null);
        }

        // Adiciona a figurinha como marcador no panorama
        characterMarker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: panorama,
            icon: {
                url: "https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExeTRweGJoMHk1eG5nb2tyOHMyMHp1ZGlpYTFoZDZ6Ym9zZ3ZkYXB6MSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9cw/bvQHYGOF8UOXqXSFir/giphy.gif",
                scaledSize: new google.maps.Size(60, 80),
                anchor: new google.maps.Point(30, 80)
            },
            visible: true
        });

        characterMarker.addListener('click', function() {
            const message = currentGincana ? 
                `${currentGincana.contexto || 'Procure no mapa e ajude a me encontrarem neste local desconhecido, buá, buá!'}` :
                'Procure no mapa e ajude a me encontrarem neste local desconhecido, buá, buá!';
                
            Swal.fire({
                title: currentGincana ? currentGincana.nome : 'Onde estou ...',
                text: message,
                icon: 'question',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
            });
        });

        // Atualiza a trueLocation para a nova gincana
        trueLocation.lat = lat;
        trueLocation.lng = lng;
        
        // Reseta as tentativas e o score
        attempts = 0;
        score = 1000;
        
        // Fecha o mapa de palpite, se estiver aberto
        toggleSidebar(false);
        
        // Reseta o marcador do palpite
        if (marker) {
            marker.setMap(null);
            marker = null;
        }
        selectedLatLng = null;
    }

    // Função global para o Google Maps
    function initMap() {
        // Carrega a primeira gincana ao inicializar
        loadNewGincana();
    }
    window.initMap = initMap;

    // Aguarda o DOM estar pronto para o restante do código
    document.addEventListener('DOMContentLoaded', function() {
        // Funções principais
        function toggleMenu() {
            const mobileMenu = document.getElementById("mobileMenu");
            if (mobileMenu) {
                mobileMenu.classList.toggle("active");
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

                if (!trueLocation.lat || !trueLocation.lng) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Localização da gincana não carregada.'
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
                    const gincanaName = currentGincana ? currentGincana.nome : 'Gincana';
                    Swal.fire({
                        icon: 'success',
                        title: `🎉 Você acertou a ${gincanaName}!`,
                        text: `Distância: ${distance.toFixed(2)} km. Parabéns!`,
                        confirmButtonText: 'Nova Gincana'
                    }).then(() => {
                        loadNewGincana();
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
                    const gincanaName = currentGincana ? currentGincana.nome : 'Gincana';
                    Swal.fire({
                        icon: 'error',
                        title: `Não foi dessa vez na ${gincanaName}!`,
                        html: `<p>Você errou todas as tentativas.</p>
                               <p>O local correto era: ${trueLocation.lat.toFixed(5)}, ${trueLocation.lng.toFixed(5)}</p>`,
                        showCancelButton: true,
                        confirmButtonText: 'Nova Gincana',
                        cancelButtonText: 'Fechar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            loadNewGincana();
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

        // Event listener para o botão "Pular Gincana"
        const skipGincanaBtn = document.getElementById("skipGincanaBtn");
        if (skipGincanaBtn) {
            skipGincanaBtn.addEventListener("click", () => {
                loadNewGincana();
            });
        }

        // Clique no info da gincana para mostrar novamente
        const gincanaInfo = document.getElementById("gincanaInfo");
        if (gincanaInfo) {
            gincanaInfo.addEventListener("click", () => {
                if (currentGincana) {
                    showGincanaInfo(currentGincana);
                }
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

