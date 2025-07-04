@extends('layouts.app')
@section('content')
    <!-- Conteúdo Central (Street View) -->
    
        <!-- Botão "Ver Mapa" -->
        <button id="openMapBtn">Ver Mapa</button>
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


<!-- Firebase Scripts - Carregados apenas uma vez -->
<!-- <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script> -->

<script>
    // Função global para o Google Maps
    function initMap() {
        const panorama = new google.maps.StreetViewPanorama(
            document.getElementById("street-view"),
            {
                position: { lat: -23.55052, lng: -46.633308 },
                pov: { heading: 165, pitch: 0 },
                zoom: 1,
                disableDefaultUI: true
            }
        );
    }
    window.initMap = initMap;

    // Aguarda o DOM estar pronto para o restante do código
    document.addEventListener('DOMContentLoaded', function() {
        // Configuração do Firebase
        // const firebaseConfig = {
        //     apiKey: "AIzaSyANaG9MwOgpuELNX2bQhfFSv52DsT3qPVA",
        //     authDomain: "gincaneiros-02jul.firebaseapp.com",
        //     projectId: "gincaneiros-02jul",
        //     storageBucket: "gincaneiros-02jul.firebasestorage.app",
        //     messagingSenderId: "971542663015",
        //     appId: "1:971542663015:web:7a07b62bc02123a67ea9c2"
        // };

        // // Inicializa Firebase apenas se não foi inicializado
        // if (!firebase.apps.length) {
        //     firebase.initializeApp(firebaseConfig);
        // }

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
        
        function showHowToPlay() {
            Swal.fire({
                title: 'Como Jogar',
                html: `
                    <p><strong>Gincaneiros</strong> é um jogo de localização divertido e direto.</p>
                    <ul style="text-align: left;">
                        <li>👤 Um jogador escolhe um local real no Street View</li>
                        <li>📍 Um desafio é gerado para os amigos encontrarem o local</li>
                        <li>🗺️ Quem chegar mais perto, ganha mais pontos</li>
                    </ul>
                    <p>Você consegue encontrar o "fulano de tal"?</p>
                `,
                icon: 'info',
                confirmButtonText: 'Entendi!',
                confirmButtonColor: '#0d6efd'
            });
        }

        function showAbout() {
            Swal.fire({
                title: 'Sobre o Gincaneiros',
                html: `
                    <p><strong>Gincaneiros</strong> é um jogo interativo e turístico de localização.</p>
                    <p>O objetivo é simples: um jogador escolhe um local no Street View e desafia os amigos (ou o mundo!) a descobrirem onde ele está.</p>
                    <p>💡 É como uma gincana moderna, baseada em mapas e intuição geográfica!</p>
                    <p style="margin-top:15px;">📬 Dúvidas ou sugestões? <br><a href="mailto:contato@gincaneiros.com">contato@gincaneiros.com</a></p>
                `,
                icon: 'question',
                confirmButtonText: 'Fechar',
                confirmButtonColor: '#0d6efd'
            });
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

        // const loginBtn = document.getElementById("loginBtn");
        // if (loginBtn) {
        //     loginBtn.addEventListener("click", googleLogin);
        // }

        // const mobileLoginBtn = document.getElementById("mobileLoginBtn");
        // if (mobileLoginBtn) {
        //     mobileLoginBtn.addEventListener("click", googleLogin);
        // }

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

<!-- Google Maps API - Carregado por último -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzEzusC_k3oEoPnqynq2N4a0aA3arzH-c" async defer></script>