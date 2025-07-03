<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gincaneiros - Painel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <!-- Novo Menu -->
    <header>
        <nav>
            <ul>
                <li><a href="#">Criar Gincana</a></li>
                <li><a href="#">Procurar Gincana</a></li>
                <li><a href="#">Gincanas Jogadas</a></li>
                <li><a href="#" onclick="showHowToPlay()">Como Jogar</a></li>
                <li><a href="#" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Nova gincana Street View aqui -->
        <div id="street-view"></div>
    </main>

    <script>
        function logout() {
            firebase.auth().signOut().then(() => {
                localStorage.removeItem('g_user');
                window.location.href = "/";
            });
        }

        function showHowToPlay() {
            Swal.fire({
                title: 'Como Jogar',
                html: `
                    <p>Você entra em uma gincana e tenta descobrir onde o outro jogador está.</p>
                    <ul>
                        <li>5 chances para acertar</li>
                        <li>Use o mapa para dar o seu palpite</li>
                        <li>Quem mais se aproximar, ganha mais pontos</li>
                    </ul>
                `,
                icon: 'info',
                confirmButtonText: 'Vamos lá!'
            });
        }
    </script>

</body>
</html>
