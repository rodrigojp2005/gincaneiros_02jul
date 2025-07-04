<header class="navbar-container">
    <nav class="navbar">
        <a href="{{ url('/') }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
            <span style="font-size: 1.5rem; font-weight: bold; color: #222;">Gincaneiros</span>
        </a>

         <button class="navbar-toggle" id="navbarToggle" aria-label="Abrir menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul class="navbar-menu" id="navbarMenu">
            <li><a href="#" onclick="showAbout()">Sobre</a></li>
            <li><a href="#" onclick="showHowToPlay()">Como Jogar</a></li>
            @if(auth()->check())
                <li><a href="{{ url('/gincana/criar') }}">Criar Gincana</a></li>
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li><a href="#" onclick="logout()">Logout</a></li>
            @else
                <li><a href="#" id="loginBtn">Login com Google</a></li>
            @endif
        </ul>
    </nav>
</header>

<script>
    function toggleMenu() {
        const navbar = document.getElementById('navbarMenu');
        const toggle = document.getElementById('navbarToggle');
        navbar.classList.toggle('active');
        toggle.classList.toggle('active');
    }

    // Fechar menu ao clicar em um link (mobile)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.navbar-menu a').forEach(link => {
            link.addEventListener('click', () => {
                const navbar = document.getElementById('navbarMenu');
                const toggle = document.getElementById('navbarToggle');
                navbar.classList.remove('active');
                toggle.classList.remove('active');
            });
        });      

        // Fechar menu ao redimensionar
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                const navbar = document.getElementById('navbarMenu');
                const toggle = document.getElementById('navbarToggle');
                navbar.classList.remove('active');
                toggle.classList.remove('active');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('navbarToggle');
    const menu = document.getElementById('navbarMenu');

    if (toggleBtn && menu) {
        toggleBtn.addEventListener('click', function() {
            menu.classList.toggle('active');
            toggleBtn.classList.toggle('active');
        });
    }
});
</script>
