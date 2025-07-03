<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gincaneiros - Painel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Estilos -->
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }

        header {
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #ddd;
        }

        .logo {
            height: 40px;
        }

        nav ul {
            display: flex;
            gap: 1rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger span {
            height: 3px;
            width: 25px;
            background-color: #333;
            margin-bottom: 4px;
            border-radius: 2px;
        }

        .menu-mobile {
            display: none;
            flex-direction: column;
            background-color: #fff;
            position: absolute;
            top: 60px;
            right: 10px;
            border: 1px solid #ccc;
            padding: 1rem;
            z-index: 1000;
        }

        .menu-mobile a {
            padding: 0.5rem 0;
            text-decoration: none;
            color: #333;
        }

        main {
            padding: 1rem;
            text-align: center;
        }

        /* footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 0.8rem;
            position: fixed;
            width: 100%;
            bottom: 0;
        } */

         footer {
            height: var(--navbar-height);
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 10;
                        color: #888;
                                    text-align: center;


        }

        /* footer {
            background-color: #f5f5f5;
            text-align: center;
            font-size: 14px;
            color: #888;
        } */

        @media (max-width: 768px) {
            nav ul {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .menu-mobile {
                display: none;
            }

            .menu-mobile.active {
                display: flex;
            }
        }
    </style>
</head>
<body>

    <header>
        <img src="/images/logo.png" alt="Gincaneiros" class="logo">

        <nav>
            <ul>
                <!-- <li><a href="#" >Criar Gincana</a></li> -->
                 <li><a href="{{ route('gincana.criar') }}">Criar Gincana</a></li>

                <li><a href="#">Procurar Gincana</a></li>
                <li><a href="#">Gincanas Jogadas</a></li>
                <li><a href="#" onclick="showHowToPlay()">Como Jogar</a></li>
                <li><a href="#" onclick="logout()">Logout</a></li>
            </ul>
        </nav>

        <div class="hamburger" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="menu-mobile" id="mobileMenu">
            <!-- <a href="#">Criar Gincana</a> -->
             <li><a href="{{ route('gincana.criar') }}">Criar Gincana</a></li>

            <a href="#">Procurar Gincana</a>
            <a href="#">Gincanas Jogadas</a>
            <a href="#" onclick="showHowToPlay()">Como Jogar</a>
            <a href="#" onclick="logout()">Logout</a>
        </div>
    </header>

    <main>
        <h2>Bem-vindo à sua Gincana!</h2>
        <div id="street-view" style="width: 100%; height: 80vh; background-color: #eee;">
            <!-- Aqui será renderizado o Street View -->
        </div>
    </main>

    <footer>
        © 2025 Gincaneiros. Todos os direitos reservados.
    </footer>

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "SUA_API_KEY",
            authDomain: "SEU_DOMINIO.firebaseapp.com",
            projectId: "SEU_PROJECT_ID",
            appId: "SEU_APP_ID"
        };
        firebase.initializeApp(firebaseConfig);

        function logout() {
            firebase.auth().signOut().then(() => {
                localStorage.removeItem('g_user');
                window.location.href = "/";
            });
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showHowToPlay() {
            Swal.fire({
                title: 'Como Jogar',
                html: `
                    <p>Você verá um personagem perdido em algum ponto do Street View.</p>
                    <p>Use o botão "Ver Mapa" para abrir o mapa e tentar acertar onde ele está.</p>
                    <p>Você tem 5 tentativas e cada acerto próximo vale mais pontos!</p>
                `,
                icon: 'info',
                confirmButtonText: 'Entendi!'
            });
        }

        function toggleMobileMenu() {
            document.getElementById("mobileMenu").classList.toggle("active");
        }
    </script>
</body>
</html>
