<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gincaneiros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Firebase App (core) -->
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>

    <style>
        :root {
            --navbar-height: 60px;
            --footer-height: 60px;
            --primary-color: #1a1a1a;
            --accent-color: #0d6efd;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
        }

        header, footer {
            height: var(--navbar-height);
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 10;
        }

        header img.logo {
        }
            height: 40px;

        nav ul {
            list-style: none;
            display: flex;
            gap: 1.2rem;
        }

        nav ul li a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
        }
.hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
}

.hamburger span {
    height: 3px;
    width: 25px;
    background: var(--primary-color);
    margin: 4px 0;
    border-radius: 3px;
}

.menu-mobile {
    display: none;
    flex-direction: column;
    gap: 1rem;
    position: absolute;
    top: var(--navbar-height);
    right: 1rem;
    background: #fff;
    padding: 1rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    z-index: 1000;
}

.menu-mobile a {
    text-decoration: none;
    color: var(--primary-color);
    font-weight: 500;
}

.menu-mobile.active {
    display: flex;
}

@media (max-width: 768px) {
    nav ul {
        display: none;
    }

    .hamburger {
        display: flex;
    }
}
        main {
            height: calc(100vh - var(--navbar-height) - var(--footer-height));
            position: relative;
        }

        #street-view {
            width: 100%;
            height: 100%;
        }

        footer {
            background-color: #f5f5f5;
            text-align: center;
            font-size: 14px;
            color: #888;
        }


        #openMapBtn {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 24px;
            font-size: 16px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        #mapSidebar {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 400px;
            height: 100vh;
            background-color: #fff;
            box-shadow: -2px 0 10px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            transition: right 0.3s ease-in-out;
            z-index: 999;
        }

        #mapSidebar.open {
            right: 0;
        }

        #map {
            flex: 1;
            width: 100%;
        }

        #confirmBtn {
            background-color: #28a745;
            color: #fff;
            padding: 15px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }

            .sidebar-header {
    padding: 10px;
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    text-align: right;
}

#closeMapBtn {
    background: none;
    border: none;
    font-size: 18px;
    color: #333;
    cursor: pointer;
}


    </style>
</head>
<body>

    <!-- Navbar -->
   <header>
    <img src="/images/logo.png" alt="Gincaneiros" class="logo">

    <nav>
        <ul id="desktopMenu">
            <li><a href="#" onclick="showAbout()">Sobre</a></li>
            <li><a href="#" onclick="showHowToPlay()">Como Jogar</a></li>
            <li><a href="#" id="loginBtn">Login</a></li>
        </ul>
    </nav>

    <!-- Botão hambúrguer -->
    <div class="hamburger" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
</header>

<!-- Menu Mobile (fora do header/nav direto) -->
<div class="menu-mobile" id="mobileMenu">
    <a href="#" onclick="showAbout()">Sobre</a>
    <a href="#" onclick="showHowToPlay()">Como Jogar</a>
    <a href="#" id="loginBtn">Login</a>
</div>


    <!-- Conteúdo Central (Street View) -->
    <main>
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
    </main>

    <!-- Rodapé -->
    <footer>
        &copy; 2025 Gincaneiros. Todos os direitos reservados.
    </footer>

    <script>
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

        // function toggleMenu() {
        //     document.getElementById("mobileMenu").classList.toggle("active");
        // }
        function toggleMenu() {
        const mobileMenu = document.getElementById("mobileMenu");
        mobileMenu.classList.toggle("active");
    }
    </script>
    <script>
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
        </script>

            <script>
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
        </script>

        <script>
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

    function toggleSidebar(open = true) {
        const sidebar = document.getElementById("mapSidebar");
        if (open) {
            sidebar.classList.add("open");
        } else {
            sidebar.classList.remove("open");
        }
    }

    document.getElementById("openMapBtn").addEventListener("click", () => {
        toggleSidebar(true);
        initMapSelector(); // inicia o mapa para seleção
    });

    document.getElementById("confirmBtn").addEventListener("click", () => {
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

        // Resetar ou finalizar a rodada aqui se quiser

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
                // Resetar para novo local se não logar
                location.reload(); // ou mudar o panorama
            }
        });
    }
});

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


    function initMapSelector() {
        if (map) return; // já foi inicializado

        map = new google.maps.Map(document.getElementById("map"), {
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

        document.getElementById("closeMapBtn").addEventListener("click", () => {
            toggleSidebar(false);
        });

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

</script>

<script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-app.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  const firebaseConfig = {
    apiKey: "AIzaSyANaG9MwOgpuELNX2bQhfFSv52DsT3qPVA",
    authDomain: "gincaneiros-02jul.firebaseapp.com",
    projectId: "gincaneiros-02jul",
    storageBucket: "gincaneiros-02jul.firebasestorage.app",
    messagingSenderId: "971542663015",
    appId: "1:971542663015:web:7a07b62bc02123a67ea9c2"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);

document.getElementById('loginBtn').addEventListener('click', () => {
  const provider = new firebase.auth.GoogleAuthProvider();
  firebase.auth().signInWithPopup(provider)
    .then((result) => {
      const user = result.user;
      localStorage.setItem('g_user', JSON.stringify(user));
      window.location.href = "/dashboard"; // redireciona para nova gincana
    })
    .catch((error) => {
      console.error(error);
      Swal.fire('Erro ao logar', error.message, 'error');
    });
});


</script>



    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzEzusC_k3oEoPnqynq2N4a0aA3arzH-c&callback=initMap" async defer></script>
</body>
</html>
