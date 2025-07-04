<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gincaneiros</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script> 
    <!-- Google Maps API - Carregado por último -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzEzusC_k3oEoPnqynq2N4a0aA3arzH-c" async defer></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    @include('partials.navbar')  <!-- Menu -->

    <main>
        @yield('content')        <!-- Conteúdo dinâmico de cada página -->
    </main>

    @include('partials.footer') <!-- Rodapé -->
    
    <!-- Scripts no final do body -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
      