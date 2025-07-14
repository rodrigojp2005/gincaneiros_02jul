<header class="navbar-container">
    <nav class="navbar">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
        </a>
        <ul class="navbar-menu" id="navbar-menu">
            <li><a href="#" onclick="showAbout()">Sobre</a></li>
            <li><a href="#" onclick="showHowToPlay()">Como Jogar</a></li>
            @if(auth()->check())
                <li><a href="{{ url('/gincana/criar') }}">Criar Gincana</a></li>
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li><a href="#" onclick="logout()">Logout</a></li>
            @else
                <li><a href="#" onclick="loginWithGoogle()">Login com Google</a></li>
            @endif
        </ul>
    </nav>
</header>
