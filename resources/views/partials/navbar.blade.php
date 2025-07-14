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
            
            <!-- Item antigo mantido para compatibilidade -->
            <li id="criarGincanaItem" style="display:none;"><a href="/gincana/criar">Criar Gincana</a></li>
            
            <!-- Submenu "Minhas Gincanas" -->
            <li id="minhasGincanasItem" style="display:none; position: relative;" class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">
                    Minhas Gincanas <span class="dropdown-arrow">▼</span>
                </a>
                <ul class="dropdown-menu" id="gincanasDropdown">
                    <!-- <li><a href="{{ route('gincana.criar') }}">+ Criar Gincana</a></li> -->
                    <li><a href="{{ route('gincana.minhas') }}">Gincanas Criadas</a></li>
                    <li><a href="{{ route('gincana.participadas') }}">Gincanas Participadas</a></li>
                </ul>
            </li>
            
            <li id="dashboardItem" style="display:none;"><a href="/dashboard">Jogar</a></li>
            <li id="logoutItem" style="display:none;"><a href="#" onclick="logout()">Logout</a></li>
            <li id="loginItem"><a href="#" id="loginBtn">Login com Google</a></li>
        </ul>
    </nav>
</header>

<style>
/* Estilos para o dropdown */
.dropdown {
    position: relative;
}

.dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 5px;
}

.dropdown-arrow {
    font-size: 0.8rem;
    transition: transform 0.2s ease;
}

.dropdown.active .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 1000;
    list-style: none;
    padding: 8px 0;
    margin: 0;
}

.dropdown.active .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-menu li {
    margin: 0;
}

.dropdown-menu a {
    display: block;
    padding: 10px 16px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.2s ease;
    border-radius: 0;
}

.dropdown-menu a:hover {
    background-color: #f8f9fa;
    color: #0d6efd;
}

/* Ajustes para mobile */
@media (max-width: 768px) {
    .dropdown-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        border: none;
        background: rgba(102, 126, 234, 0.05);
        border-radius: 0;
        margin-top: 10px;
        padding: 0;
    }
    
    .dropdown-menu a {
        padding: 12px 24px;
        font-size: 1rem;
        background: rgba(102, 126, 234, 0.1);
        margin: 5px 0;
        border-radius: 8px;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }
    
    .dropdown-menu a:hover {
        background: #667eea;
        color: white;
    }
    
    .dropdown-menu {
        display: none;
    }
    
    .dropdown.active .dropdown-menu {
        display: block;
    }
}
</style>

<script>
    function toggleMenu() {
        const navbar = document.getElementById('navbarMenu');
        const toggle = document.getElementById('navbarToggle');
        navbar.classList.toggle('active');
        toggle.classList.toggle('active');
    }

    function toggleDropdown(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const dropdown = event.target.closest('.dropdown');
        const isActive = dropdown.classList.contains('active');
        
        // Fecha todos os dropdowns
        document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
        
        // Abre o dropdown clicado se não estava ativo
        if (!isActive) {
            dropdown.classList.add('active');
        }
    }

    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
        }
    });

    // Fechar menu ao clicar em um link (mobile)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.navbar-menu a:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', () => {
                const navbar = document.getElementById('navbarMenu');
                const toggle = document.getElementById('navbarToggle');
                navbar.classList.remove('active');
                toggle.classList.remove('active');
                
                // Fecha dropdowns também
                document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
            });
        });      

        // Fechar menu ao redimensionar
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                const navbar = document.getElementById('navbarMenu');
                const toggle = document.getElementById('navbarToggle');
                navbar.classList.remove('active');
                toggle.classList.remove('active');
                
                // Fecha dropdowns também
                document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
            }
        });

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

