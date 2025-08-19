<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sin Conexión - Mi Economía Personal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'float': 'float 3s ease-in-out infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {opacity: '0', transform: 'translateY(20px)'},
                            '100%': {opacity: '1', transform: 'translateY(0)'}
                        },
                        slideUp: {
                            '0%': {opacity: '0', transform: 'translateY(40px)'},
                            '100%': {opacity: '1', transform: 'translateY(0)'}
                        },
                        float: {
                            '0%, 100%': {transform: 'translateY(0px)'},
                            '50%': {transform: 'translateY(-10px)'}
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
<div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
    <!-- Main Container -->
    <div class="max-w-md w-full text-center animate-fade-in">
        <!-- Offline Icon -->
        <div class="mb-8 animate-float">
            <div
                class="w-24 h-24 mx-auto bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18.364 5.636l-12.728 12.728m0-12.728l12.728 12.728M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-4 animate-slide-up">
            Sin Conexión a Internet
        </h1>

        <!-- Description -->
        <p class="text-gray-600 mb-8 leading-relaxed animate-slide-up" style="animation-delay: 0.2s;">
            No tienes conexión a internet en este momento. Vuelve mas tarde
        </p>

        <!-- Action Buttons -->
        <div class="space-y-4 animate-slide-up" style="animation-delay: 0.6s;">
            <!-- Try Again Button -->
            <button
                onclick="checkConnection()"
                id="retry-btn"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span id="retry-text">Intentar Conectar</span>
            </button>

            <!-- Continue Offline Button -->
            <button
                onclick="goToApp()"
                class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
                Continuar Sin Conexión
            </button>
        </div>

        <!-- Connection Status -->
        <div class="mt-8 animate-slide-up" style="animation-delay: 0.8s;">
            <div id="connection-status" class="flex items-center justify-center gap-2 text-sm text-gray-500">
                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                <span>Estado: Sin conexión</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500 animate-fade-in" style="animation-delay: 1.2s;">
        <p>Control Financiero - Versión Offline</p>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
        <p class="text-gray-700 font-medium">Verificando conexión...</p>
    </div>
</div>

<script>
    // Check if online/offline
    function updateConnectionStatus() {
        const statusElement = document.getElementById('connection-status');
        const statusDot = statusElement.querySelector('div');
        const statusText = statusElement.querySelector('span');

        if (navigator.onLine) {
            statusDot.className = 'w-3 h-3 bg-green-500 rounded-full animate-pulse';
            statusText.textContent = 'Estado: Conectado';

            // Auto redirect if online
            setTimeout(() => {
                window.location.href = '/';
            }, 2000);
        } else {
            statusDot.className = 'w-3 h-3 bg-red-500 rounded-full animate-pulse';
            statusText.textContent = 'Estado: Sin conexión';
        }
    }

    // Check connection manually
    function checkConnection() {
        const retryBtn = document.getElementById('retry-btn');
        const retryText = document.getElementById('retry-text');
        const loadingOverlay = document.getElementById('loading-overlay');

        // Show loading
        loadingOverlay.classList.remove('hidden');
        retryBtn.disabled = true;
        retryText.textContent = 'Verificando...';

        // Simulate connection check
        setTimeout(() => {
            loadingOverlay.classList.add('hidden');
            retryBtn.disabled = false;
            retryText.textContent = 'Intentar Conectar';

            if (navigator.onLine) {
                // Try to fetch a small resource to verify real connectivity
                fetch('/', {method: 'HEAD', cache: 'no-cache'})
                    .then(() => {
                        showSuccessMessage();
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 1500);
                    })
                    .catch(() => {
                        showErrorMessage();
                    });
            } else {
                showErrorMessage();
            }
        }, 2000);
    }

    // Go to app in offline mode
    function goToApp() {
        // Store offline mode flag
        localStorage.setItem('offlineMode', 'true');
        window.location.href = '/';
    }

    // Show success message
    function showSuccessMessage() {
        const message = document.createElement('div');
        message.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-up';
        message.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    ¡Conexión restaurada!
                </div>
            `;
        document.body.appendChild(message);

        setTimeout(() => {
            message.remove();
        }, 3000);
    }

    // Show error message
    function showErrorMessage() {
        const message = document.createElement('div');
        message.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-up';
        message.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Aún sin conexión
                </div>
            `;
        document.body.appendChild(message);

        setTimeout(() => {
            message.remove();
        }, 3000);
    }

    // Listen for online/offline events
    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);

    // Initial status check
    updateConnectionStatus();

    // Periodic connection check
    setInterval(updateConnectionStatus, 5000);

    // Add some interactive effects
    document.addEventListener('DOMContentLoaded', function () {
        // Add click effect to feature cards
        const featureCards = document.querySelectorAll('.bg-green-50, .bg-blue-50, .bg-purple-50');
        featureCards.forEach(card => {
            card.addEventListener('click', function () {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });
</script>
</body>
</html>
