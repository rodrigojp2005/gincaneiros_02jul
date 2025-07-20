// ========================================================================
//  ⚠️ AVISO DE SEGURANÇA ⚠️
//  Suas chaves de API foram removidas para sua proteção.
//  NUNCA compartilhe chaves de API publicamente.
//  Vá ao Google Cloud e Firebase, gere NOVAS chaves e restrinja o uso
//  delas ao domínio do seu site.
// ========================================================================

/* ---------- Configurações das APIs ---------- */

// Configuração do Firebase
const firebaseConfig = {
    apiKey: '/* ⚠️ COLE SUA NOVA CHAVE DE API DO FIREBASE AQUI ⚠️ */',
    authDomain: 'gincaneiros-02jul.firebaseapp.com',
    projectId: 'gincaneiros-02jul',
    storageBucket: 'gincaneiros-02jul.firebasestorage.app',
    messagingSenderId: '971542663015',
    appId: '1:971542663015:web:7a07b62bc02123a67ea9c2',
};

// Chave da API do Google Maps
const Maps_API_KEY = 'AIzaSyBzEzusC_k3oEoPnqynq2N4a0aA3arzH-c';

/* ---------- Funções de Carregamento de Scripts (Loaders) ---------- */

/**
 * Esta é a função que o Google vai chamar QUANDO a API do Maps estiver pronta.
 * Ela funciona como um "sinal verde" para iniciar o jogo.
 */
const onGoogleMapsLoaded = () => {
    console.log('API do Google Maps carregada com sucesso. Iniciando o jogo...');
    // Agora que 'google' existe, podemos chamar a função principal do jogo.
    initGame();
};
// É ESSENCIAL tornar a função global para que o script do Google possa encontrá-la.
window.onGoogleMapsLoaded = onGoogleMapsLoaded;

/**
 * Cria e injeta a tag <script> do Google Maps na página.
 * O parâmetro `&callback=onGoogleMapsLoaded` é a chave de tudo.
 */
const loadGoogleMaps = () => {
    if (window.google) {
        // Se a API já foi carregada por algum motivo, inicia o jogo diretamente.
        console.log('API do Google Maps já estava carregada.');
        onGoogleMapsLoaded();
        return;
    }
    console.log('Carregando a API do Google Maps...');
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${Maps_API_KEY}&libraries=places,geometry&callback=onGoogleMapsLoaded`;
    script.async = true;
    script.onerror = () => console.error('Falha ao carregar o script do Google Maps. Verifique a chave de API e a conexão.');
    document.head.appendChild(script);
};

/**
 * Carrega o script do Firebase.
 */
const loadFirebase = () => {
    if (!window.firebase?.apps?.length) {
        console.log('Carregando Firebase...');
        const fbScript = document.createElement('script');
        fbScript.src = 'https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js';
        fbScript.onload = () => firebase.initializeApp(firebaseConfig);
        document.head.appendChild(fbScript);
    }
};

/**
 * Carrega o script do SweetAlert2 para os pop-ups.
 */
const loadSwal = () => {
    console.log('Carregando SweetAlert2...');
    const swalScript = document.createElement('script');
    swalScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    document.head.appendChild(swalScript);
};


/* ---------- Variáveis e Configurações do Jogo ---------- */
let selectedLatLng = null;
let map;
let marker;
let panorama;
let characterMarker;
let currentGincana = null;

const trueLocation = { lat: null, lng: null };
let attempts = 0;
const maxAttempts = 5;
let score = 1000;

/* ---------- Funções Principais do Jogo ---------- */

// Abre e fecha a barra lateral do mapa
function toggleSidebar(open = true) {
    document.getElementById('mapSidebar')?.classList.toggle('open', open);
}

// Mostra informações da gincana atual
function showGincanaInfo(gincana) {
    const infoDiv = document.getElementById('gincanaInfo');
    const nameElement = document.getElementById('gincanaName');
    const contextElement = document.getElementById('gincanaContext');
    if (infoDiv && nameElement && contextElement) {
        nameElement.textContent = gincana.nome || 'Gincana';
        contextElement.textContent = gincana.contexto || 'Descubra onde estou!';
        infoDiv.style.display = 'block';
        setTimeout(() => (infoDiv.style.display = 'none'), 6000);
    }
}

// Carrega uma nova gincana (atualmente com dados de exemplo)
function loadNewGincana() {
    const mock = {
        lat: -23.55052,
        lng: -46.633308,
        nome: 'Gincana Teste',
        contexto: 'Local de teste – substitua pela rota real depois!',
    };
    currentGincana = mock;
    updateGameLocation(mock.lat, mock.lng);
    showGincanaInfo(mock);
}

// Atualiza o Street View com a nova localização
function updateGameLocation(lat, lng) {
    if (!google) {
        console.error('O objeto "google" não está disponível. A API não foi carregada corretamente.');
        return;
    }
    const streetViewElement = document.getElementById('street-view');
    if (!streetViewElement) return;

    panorama = new google.maps.StreetViewPanorama(streetViewElement, {
        position: { lat, lng },
        pov: { heading: 300, pitch: 0 },
        zoom: 1,
        disableDefaultUI: true,
    });

    if (characterMarker) characterMarker.setMap(null);
    characterMarker = new google.maps.Marker({
        position: { lat, lng },
        map: panorama,
        icon: {
            url: 'https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExeTRweGJoMHk1eG5nb2tyOHMyMHp1ZGlpYTFoZDZ6Ym9zZ3ZkYXB6MSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9cw/bvQHYGOF8UOXqXSFir/giphy.gif',
            scaledSize: new google.maps.Size(60, 80),
            anchor: new google.maps.Point(30, 80),
        },
        visible: true,
    });

    characterMarker.addListener('click', () => {
        Swal.fire({
            title: currentGincana?.nome || 'Onde estou ...',
            text: currentGincana?.contexto || 'Procure no mapa!',
            icon: 'question',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
    });

    trueLocation.lat = lat;
    trueLocation.lng = lng;
    attempts = 0;
    score = 1000;
    toggleSidebar(false);

    if (marker) {
        marker.setMap(null);
        marker = null;
    }
    selectedLatLng = null;
}

// Inicializa o mapa para o jogador dar o palpite
function initMapSelector() {
    if (map) return; // Não inicializar duas vezes
    const mapElement = document.getElementById('map');
    if (!mapElement || !google) return;

    map = new google.maps.Map(mapElement, {
        center: { lat: -23.55052, lng: -46.633308 },
        zoom: 4,
    });

    map.addListener('click', (e) => {
        selectedLatLng = { lat: e.latLng.lat(), lng: e.latLng.lng() };
        if (marker) marker.setMap(null);
        marker = new google.maps.Marker({
            position: selectedLatLng,
            map,
            title: 'Seu palpite',
        });
    });
}

/* ---------- Funções de Lógica de Pontuação e Dicas ---------- */
function getDirectionHint(from, to) {
    const latDiff = to.lat - from.lat;
    const lngDiff = to.lng - from.lng;
    return Math.abs(latDiff) > Math.abs(lngDiff)
        ? (latDiff > 0 ? 'norte' : 'sul')
        : (lngDiff > 0 ? 'leste' : 'oeste');
}

function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * (Math.PI / 180);
    const dLon = (lon2 - lon1) * (Math.PI / 180);
    const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos(lat1 * (Math.PI / 180)) *
        Math.cos(lat2 * (Math.PI / 180)) *
        Math.sin(dLon / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// Lógica de confirmação do palpite
function confirmGuess() {
    if (!selectedLatLng) {
        Swal.fire('Escolha um local', 'Clique no mapa para dar seu palpite antes de confirmar.', 'warning');
        return;
    }

    const distance = getDistanceFromLatLonInKm(selectedLatLng.lat, selectedLatLng.lng, trueLocation.lat, trueLocation.lng);
    attempts++;

    if (distance <= 1) { // Acertou
        Swal.fire({
            icon: 'success',
            title: '🎉 Acertou!',
            html: `Distância: <strong>${distance.toFixed(2)} km</strong>. Parabéns!`,
            confirmButtonText: 'Nova Gincana',
        }).then(() => loadNewGincana());
    } else if (attempts < maxAttempts) { // Errou, mas tem mais tentativas
        score -= 200;
        const hint = getDirectionHint(selectedLatLng, trueLocation);
        Swal.fire({
            icon: 'info',
            title: 'Quase lá...',
            html: `Você está a <strong>${distance.toFixed(1)} km</strong>.<br>Dica: tente mais ao <strong>${hint}</strong>.<br>Tentativas restantes: ${maxAttempts - attempts}`,
            confirmButtonText: 'Tentar de novo',
        });
    } else { // Errou e acabaram as tentativas
        Swal.fire({
            icon: 'error',
            title: 'Acabaram as tentativas!',
            html: `O local correto era:<br><strong>${trueLocation.lat.toFixed(5)}, ${trueLocation.lng.toFixed(5)}</strong>`,
            confirmButtonText: 'Nova Gincana',
        }).then(() => loadNewGincana());
    }
}

/* ---------- Funções de Autenticação e Diálogos (expostas globalmente) ---------- */
window.googleLogin = () => {
    const provider = new firebase.auth.GoogleAuthProvider();
    firebase.auth().signInWithPopup(provider)
        .then(result => {
            localStorage.setItem('g_user', JSON.stringify(result.user));
            window.location.href = '/dashboard';
        })
        .catch(err => Swal.fire('Erro ao logar', err.message, 'error'));
};

window.logout = () => {
    firebase.auth().signOut()
        .then(() => {
            localStorage.removeItem('g_user');
            window.location.href = '/';
        })
        .catch(err => Swal.fire('Erro ao deslogar', err.message, 'error'));
};

window.showHowToPlay = () => {
    Swal.fire({
        title: 'Como Jogar',
        html: `<p><strong>Gincaneiros</strong> é um jogo de localização divertido.</p><ul style="text-align:left; display:inline-block;"><li>👤 Veja um local no Street View.</li><li>🗺️ Abra o mapa e clique onde acha que é.</li><li>🏆 Quanto mais perto, mais pontos!</li></ul>`,
        icon: 'info',
        confirmButtonText: 'Entendi!',
        confirmButtonColor: '#0d6efd',
    });
};

window.showAbout = () => {
    Swal.fire({
        title: 'Sobre o Gincaneiros',
        html: `<p>Descubra lugares incríveis com este jogo interativo!</p><p style="margin-top:15px;">📬 Dúvidas? <a href="mailto:contato@gincaneiros.com">contato@gincaneiros.com</a></p>`,
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#0d6efd',
    });
};

/* ---------- Função Principal de Inicialização do Jogo ---------- */

/**
 * Esta função SÓ É CHAMADA depois que a API do Google está pronta.
 * Ela configura os eventos dos botões e carrega a primeira gincana.
 */
const initGame = () => {
    // Adiciona os eventos aos botões da interface
    document.getElementById('openMapBtn')?.addEventListener('click', () => {
        toggleSidebar(true);
        initMapSelector();
    });
    document.getElementById('closeMapBtn')?.addEventListener('click', () => toggleSidebar(false));
    document.getElementById('confirmBtn')?.addEventListener('click', confirmGuess);
    document.getElementById('comoJogarBtn')?.addEventListener('click', window.showHowToPlay);
    document.getElementById('sobreBtn')?.addEventListener('click', window.showAbout);

    // Carrega a primeira gincana para o jogador
    loadNewGincana();
};


/* ========================================================================
   PONTO DE PARTIDA - INICIA O CARREGAMENTO DOS SCRIPTS
   ======================================================================== */

// 1. Começa carregando os scripts que não dependem de nada.
loadFirebase();
loadSwal();

// 2. Inicia o carregamento da API do Google Maps.
//    O restante da inicialização do jogo (initGame) acontecerá
//    automaticamente através da função de callback 'onGoogleMapsLoaded'.
loadGoogleMaps();