       const firebaseConfig = {
            apiKey: "AIzaSyANaG9MwOgpuELNX2bQhfFSv52DsT3qPVA",
            authDomain: "gincaneiros-02jul.firebaseapp.com",
            projectId: "gincaneiros-02jul",
            storageBucket: "gincaneiros-02jul.firebasestorage.app",
            messagingSenderId: "971542663015",
            appId: "1:971542663015:web:7a07b62bc02123a67ea9c2"
        };

        // Inicializa Firebase apenas se não foi inicializado
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        // Verifica se o usuário já está logado
        firebase.auth().onAuthStateChanged((user) => {
            if (user) {
                localStorage.setItem('g_user', JSON.stringify(user));
                document.getElementById('loginBtn').textContent = 'Logout';
                document.getElementById('loginBtn').setAttribute('onclick', 'logout()');
            } else {
                localStorage.removeItem('g_user');
                document.getElementById('loginBtn').textContent = 'Login com Google';
                document.getElementById('loginBtn').removeAttribute('onclick');
            }
        });

          // Função global
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

        function logout() {
            firebase.auth().signOut().then(() => {
                localStorage.removeItem('g_user');
                window.location.href = "/";
            }).catch((error) => {
                console.error("Erro ao fazer logout:", error);
                Swal.fire('Erro ao deslogar', error.message, 'error');
            });
        }   

        const loginBtn = document.getElementById('loginBtn');
        if (loginBtn) {
            loginBtn.addEventListener('click', googleLogin);
        }

        // ...depois de verificar o usuário logado no Firebase...
firebase.auth().onAuthStateChanged(function(user) {
    if (user) {
        document.getElementById('criarGincanaItem').style.display = '';
        document.getElementById('dashboardItem').style.display = '';
        document.getElementById('logoutItem').style.display = '';
        document.getElementById('loginItem').style.display = 'none';
        // Exibir saudação
        document.getElementById('userGreeting').textContent = `Olá, ${user.displayName}`;
        document.getElementById('userGreeting').style.display = 'inline';
    } else {
        document.getElementById('criarGincanaItem').style.display = 'none';
        document.getElementById('dashboardItem').style.display = 'none';
        document.getElementById('logoutItem').style.display = 'none';
        document.getElementById('loginItem').style.display = '';
        document.getElementById('userGreeting').style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const sobreBtn = document.getElementById('sobreBtn');
    if (sobreBtn) {
        sobreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showAbout();
        });
    }

    const comoJogarBtn = document.getElementById('comoJogarBtn');
    if (comoJogarBtn) {
        comoJogarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showHowToPlay();
        });
    }
});

function showAbout() {
    Swal.fire({
        title: 'Sobre o Gincaneiros',
        html: `
            <p style="margin-top:15px;">📬 Dúvidas ou sugestões? <br><a href="mailto:contato@gincaneiros.com">contato@gincaneiros.com</a></p>
        `,
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#0d6efd'
    });
}

function showHowToPlay() {
    Swal.fire({
        title: 'Como Jogar',
        html: `
            <p><strong>Gincaneiros</strong> é um jogo de localização divertido e direto.</p>
            <ul style="text-align: left;">
                <li>Explore o mapa e tente encontrar o local correto.</li>
                <li>Você tem um número limitado de tentativas.</li>
            </ul>
            <p>Você consegue encontrar o "fulano de tal"?</p>
        `,
        icon: 'info',
        confirmButtonText: 'Entendi!',
        confirmButtonColor: '#0d6efd'
    });
}
