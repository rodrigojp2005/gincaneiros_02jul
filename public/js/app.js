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
