<header class="navbar-container">
    <nav class="navbar">
        <a href="{{ url('/') }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
            <span style="font-size: 1.5rem; font-weight: bold; color: #222;">Gincaneiros</span>
        </a>
            <span id="userGreeting" style="display:none; pointer-events:none; color:#222; font-weight:bold;"></span>
         <button class="navbar-toggle" id="navbarToggle" aria-label="Abrir menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul class="navbar-menu" id="navbarMenu">
            <li><a href="#" id="sobreBtn">Sobre</a></li>
            <li><a href="#" id="comoJogarBtn">Como Jogar</a></li>
            <li id="criarGincanaItem" style="display:none;"><a href="/gincana/criar">Criar Gincana</a></li>
            <li id="dashboardItem" style="display:none;"><a href="/dashboard">Dashboard</a></li>
            <li id="logoutItem" style="display:none;"><a href="#" onclick="logout()">Logout</a></li>
            <li id="loginItem"><a href="#" id="loginBtn">Login com Google</a></li>
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
