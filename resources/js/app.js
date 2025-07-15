import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

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

        // Função para atualizar a visibilidade dos elementos do menu
        function updateMenuVisibility(user) {
            // Elementos do menu antigo
            const criarGincanaItem = document.getElementById('criarGincanaItem');
            const logoutItem = document.getElementById('logoutItem');
            const loginItem = document.getElementById('loginItem');
            const userGreeting = document.getElementById('userGreeting');
            
            // Novo elemento do menu "Minhas Gincanas"
            const minhasGincanasItem = document.getElementById('minhasGincanasItem');

            if (user) {
                // Usuário logado
                if (criarGincanaItem) criarGincanaItem.style.display = '';
                if (minhasGincanasItem) minhasGincanasItem.style.display = '';
                if (logoutItem) logoutItem.style.display = '';
                if (loginItem) loginItem.style.display = 'none';
                
                // Exibir saudação só com o primeiro nome
                if (userGreeting) {
                    const firstName = user.displayName ? user.displayName.split(' ')[0] : '';
                    userGreeting.textContent = `Olá, ${firstName}`;
                    userGreeting.style.display = 'inline';
                }
                
                // Atualizar link do logo para dashboard
                updateLogoLink(true);
            } else {
                // Usuário não logado
                if (criarGincanaItem) criarGincanaItem.style.display = 'none';
                if (minhasGincanasItem) minhasGincanasItem.style.display = 'none';
                if (logoutItem) logoutItem.style.display = 'none';
                if (loginItem) loginItem.style.display = '';
                if (userGreeting) userGreeting.style.display = 'none';
                
                // Atualizar link do logo para welcome
                updateLogoLink(false);
            }
        }

        // Função para atualizar o link do logo baseado no status de login
        function updateLogoLink(isLoggedIn) {
            const logoLink = document.getElementById('logoLink');
            if (logoLink) {
                if (isLoggedIn) {
                    logoLink.href = '/dashboard';
                } else {
                    logoLink.href = '/';
                }
            }
        }

        // Verifica se o usuário já está logado
        firebase.auth().onAuthStateChanged((user) => {
            updateMenuVisibility(user);
            
            const loginBtn = document.getElementById('loginBtn');
            if (loginBtn) {
                if (user) {
                    localStorage.setItem('g_user', JSON.stringify(user));
                    loginBtn.textContent = 'Logout';
                    loginBtn.setAttribute('onclick', 'logout()');
                } else {
                    localStorage.removeItem('g_user');
                    loginBtn.textContent = 'Login com Google';
                    loginBtn.removeAttribute('onclick');
                }
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

        // Aguarda o DOM estar pronto antes de adicionar event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const loginBtn = document.getElementById('loginBtn');
            if (loginBtn) {
                loginBtn.addEventListener('click', googleLogin);
            }

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

            // Configurar o link do logo inicialmente
            // Se já há um usuário no localStorage, configura como logado
            const storedUser = localStorage.getItem('g_user');
            if (storedUser) {
                updateLogoLink(true);
            } else {
                updateLogoLink(false);
            }
        });

        function showAbout() {
            Swal.fire({
                title: 'Sobre o Gincaneiros',
                html: `
               <p><strong>Gincaneiros</strong> é um jogo interativo e turístico de localização.</p>
                             <p>O objetivo é simples: um jogador escolhe um local no Street View e desafia os amigos (ou o mundo!) a descobrirem onde ele está.</p>
                             <p>💡 É como uma gincana moderna, baseada em mapas e intuição geográfica!</p>
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
                             <ul style="text-align: center;">
                                 <li>👤 Um jogador escolhe um local real no Street View.</li>
                                 <li>📍 Um desafio é gerado para os amigos encontrarem o local.</li>
                                 <li>🗺️ Quem chegar mais perto, ganha mais pontos.</li>
                                <li>🏆 O jogador com mais pontos no final vence.</li>
                            </ul>
                `,
                icon: 'info',
                confirmButtonText: 'Entendi!',
                confirmButtonColor: '#0d6efd'
            });
        }

