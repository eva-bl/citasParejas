<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="landingPage()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Registra, valora y guarda los mejores momentos de tu relación. Descubre qué planes disfrutan más juntos.">
    <title>{{ config('app.name', 'Valorar Planes en Pareja') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-pink-50 via-purple-50 to-blue-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 min-h-screen">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-neutral-900/80 backdrop-blur-md border-b border-neutral-200 dark:border-neutral-800 transition-all duration-300" 
         :class="scrolled ? 'shadow-lg' : ''">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center space-x-2 hover:opacity-80 transition-opacity">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-xl font-bold">💑</span>
                    </div>
                    <span class="text-xl font-bold text-neutral-900 dark:text-white">Citas</span>
                </a>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white transition-colors">
                            Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg text-sm font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center" 
                 x-data="{ 
                     show: false,
                     init() {
                         setTimeout(() => this.show = true, 100);
                     }
                 }"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <h1 class="text-5xl md:text-7xl font-bold text-neutral-900 dark:text-white mb-6 leading-tight">
                    <span class="bg-gradient-to-r from-pink-600 via-purple-600 to-blue-600 bg-clip-text text-transparent">
                        Valorar Planes en Pareja
                    </span>
                </h1>
                <p class="text-xl md:text-2xl text-neutral-600 dark:text-neutral-400 mb-8 max-w-3xl mx-auto">
                    Registra, valora y guarda los mejores momentos de tu relación. 
                    Descubre qué planes disfrutan más juntos.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl text-lg font-semibold hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl text-lg font-semibold hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                            Comenzar Gratis
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white rounded-xl text-lg font-semibold border-2 border-neutral-200 dark:border-neutral-700 hover:border-pink-500 transition-all duration-200">
                            Ya tengo cuenta
                        </a>
                    @endauth
                </div>
                <p class="mt-6 text-sm text-neutral-500 dark:text-neutral-400">
                    ✨ 100% Gratis • Sin tarjetas de crédito • Configuración en minutos
                </p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white/50 dark:bg-neutral-800/50">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center text-neutral-900 dark:text-white mb-4">
                ¿Cómo funciona?
            </h2>
            <p class="text-center text-neutral-600 dark:text-neutral-400 mb-16 max-w-2xl mx-auto">
                En solo 3 pasos simples, comienza a crear recuerdos inolvidables juntos
            </p>
            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Step 1 -->
                <div class="text-center"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-20 h-20 bg-gradient-to-br from-pink-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-white shadow-lg">
                        1
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Crea tu Pareja</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Regístrate y crea una pareja con tu compañero/a usando un código único. Es rápido y seguro.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500 delay-200"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-white shadow-lg">
                        2
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Registra Planes</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Crea planes, añade detalles, fotos y organiza todas vuestras citas en un solo lugar.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500 delay-400"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-white shadow-lg">
                        3
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Valora y Disfruta</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Valora cada experiencia y descubre qué planes disfrutan más a través de estadísticas detalladas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center text-neutral-900 dark:text-white mb-4">
                ¿Qué puedes hacer?
            </h2>
            <p class="text-center text-neutral-600 dark:text-neutral-400 mb-16 max-w-2xl mx-auto">
                Todas las herramientas que necesitas para hacer cada momento especial
            </p>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-neutral-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">📅</span>
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Registra tus Planes</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Crea y organiza todos los planes que realizan juntos. Desde cenas románticas hasta aventuras emocionantes.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-neutral-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500 delay-100"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">⭐</span>
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Valora cada Experiencia</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Evalúa cada plan con criterios específicos: diversión, conexión emocional, organización, relación calidad-precio y más.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-neutral-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500 delay-200"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">📸</span>
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Guarda Recuerdos</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Sube fotos de cada plan y crea un álbum digital de vuestros mejores momentos juntos.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white dark:bg-neutral-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500 delay-300"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">📊</span>
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Estadísticas Inteligentes</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Descubre qué tipo de planes disfrutan más, sus categorías favoritas y la evolución de vuestra relación.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white dark:bg-neutral-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500 delay-400"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">🏆</span>
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Gamificación</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Desbloquea insignias y logros mientras crean recuerdos juntos. ¡Haz que cada plan cuente!
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white dark:bg-neutral-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500 delay-500"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-pink-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">💕</span>
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Historial de Relación</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Mantén un registro completo y organizado de todos los planes que han compartido a lo largo del tiempo.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white/50 dark:bg-neutral-800/50">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-x-10"
                     x-transition:enter-end="opacity-100 translate-x-0">
                    <h2 class="text-4xl font-bold text-neutral-900 dark:text-white mb-6">
                        Por qué elegir nuestra aplicación
                    </h2>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">✓</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-2">100% Privado y Seguro</h3>
                                <p class="text-neutral-600 dark:text-neutral-400">Tus datos están completamente protegidos. Solo tú y tu pareja pueden ver vuestros planes.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">✓</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-2">Completamente Gratis</h3>
                                <p class="text-neutral-600 dark:text-neutral-400">Sin costes ocultos, sin suscripciones. Disfruta de todas las funcionalidades sin pagar nada.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-pink-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">✓</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-2">Fácil de Usar</h3>
                                <p class="text-neutral-600 dark:text-neutral-400">Interfaz intuitiva diseñada para que puedas empezar a usarla en minutos.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4"
                     x-data="{ show: false }"
                     x-intersect="show = true"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-700 delay-200"
                     x-transition:enter-start="opacity-0 translate-x-10"
                     x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl p-8 text-white">
                        <div class="text-4xl font-bold mb-2">∞</div>
                        <div class="text-lg font-semibold">Planes Ilimitados</div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl p-8 text-white">
                        <div class="text-4xl font-bold mb-2">📸</div>
                        <div class="text-lg font-semibold">Fotos Ilimitadas</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-pink-600 rounded-2xl p-8 text-white">
                        <div class="text-4xl font-bold mb-2">📊</div>
                        <div class="text-lg font-semibold">Estadísticas</div>
                    </div>
                    <div class="bg-gradient-to-br from-pink-500 to-blue-600 rounded-2xl p-8 text-white">
                        <div class="text-4xl font-bold mb-2">🏆</div>
                        <div class="text-lg font-semibold">Insignias</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-bold text-center text-neutral-900 dark:text-white mb-16">
                Preguntas Frecuentes
            </h2>
            <div class="space-y-4">
                <div x-data="{ open: false }" class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg overflow-hidden">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <span class="font-semibold text-neutral-900 dark:text-white">¿Es realmente gratis?</span>
                        <span class="text-2xl" x-text="open ? '−' : '+'"></span>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="px-6 pb-4 text-neutral-600 dark:text-neutral-400">
                        Sí, completamente gratis. No hay costes ocultos, no hay suscripciones, y todas las funcionalidades están disponibles sin pagar nada.
                    </div>
                </div>

                <div x-data="{ open: false }" class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg overflow-hidden">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <span class="font-semibold text-neutral-900 dark:text-white">¿Mis datos están seguros?</span>
                        <span class="text-2xl" x-text="open ? '−' : '+'"></span>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="px-6 pb-4 text-neutral-600 dark:text-neutral-400">
                        Absolutamente. Tus datos están completamente privados. Solo tú y tu pareja pueden ver vuestros planes, valoraciones y fotos. No compartimos información con terceros.
                    </div>
                </div>

                <div x-data="{ open: false }" class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg overflow-hidden">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <span class="font-semibold text-neutral-900 dark:text-white">¿Cómo me uno a mi pareja?</span>
                        <span class="text-2xl" x-text="open ? '−' : '+'"></span>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="px-6 pb-4 text-neutral-600 dark:text-neutral-400">
                        Es muy simple. Una persona crea la pareja y obtiene un código único. La otra persona se registra e ingresa ese código para unirse. ¡Listo en menos de 2 minutos!
                    </div>
                </div>

                <div x-data="{ open: false }" class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg overflow-hidden">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <span class="font-semibold text-neutral-900 dark:text-white">¿Puedo exportar mis datos?</span>
                        <span class="text-2xl" x-text="open ? '−' : '+'"></span>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="px-6 pb-4 text-neutral-600 dark:text-neutral-400">
                        Sí, puedes exportar todos tus planes y datos en formato PDF o CSV. Tus recuerdos siempre serán tuyos.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-gradient-to-r from-pink-500 via-purple-600 to-blue-600 rounded-3xl p-12 shadow-2xl"
                 x-data="{ show: false }"
                 x-intersect="show = true"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    ¿Listos para comenzar?
                </h2>
                <p class="text-xl text-white/90 mb-8">
                    Únete a otras parejas que ya están creando recuerdos inolvidables juntos.
                </p>
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-block px-8 py-4 bg-white text-purple-600 rounded-xl text-lg font-semibold hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                        Ir al Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-purple-600 rounded-xl text-lg font-semibold hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                        Crear Cuenta Gratis
                    </a>
                @endauth
                <p class="mt-6 text-white/80 text-sm">
                    Sin tarjetas de crédito • Configuración en 2 minutos • 100% gratis
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-4 sm:px-6 lg:px-8 border-t border-neutral-200 dark:border-neutral-800 bg-white/50 dark:bg-neutral-800/50">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl font-bold">💑</span>
                        </div>
                        <span class="text-xl font-bold text-neutral-900 dark:text-white">Citas</span>
                    </div>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        Haciendo cada momento especial juntos.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 dark:text-white mb-4">Producto</h4>
                    <ul class="space-y-2 text-sm text-neutral-600 dark:text-neutral-400">
                        <li><a href="#" class="hover:text-neutral-900 dark:hover:text-white transition-colors">Características</a></li>
                        <li><a href="#" class="hover:text-neutral-900 dark:hover:text-white transition-colors">Precios</a></li>
                        <li><a href="#" class="hover:text-neutral-900 dark:hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 dark:text-white mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-neutral-600 dark:text-neutral-400">
                        <li><a href="#" class="hover:text-neutral-900 dark:hover:text-white transition-colors">Privacidad</a></li>
                        <li><a href="#" class="hover:text-neutral-900 dark:hover:text-white transition-colors">Términos</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 dark:text-white mb-4">Soporte</h4>
                    <ul class="space-y-2 text-sm text-neutral-600 dark:text-neutral-400">
                        <li><a href="#" class="hover:text-neutral-900 dark:hover:text-white transition-colors">Ayuda</a></li>
                        <li><a href="#" class="hover:text-neutral-900 dark:hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-neutral-200 dark:border-neutral-800 text-center">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    © {{ date('Y') }} Citas. Hecho con 💕 para parejas que quieren hacer cada momento especial.
                </p>
            </div>
        </div>
    </footer>

    <script>
        function landingPage() {
            return {
                scrolled: false,
                init() {
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.scrollY > 20;
                    });
                }
            }
        }
    </script>
</body>
</html>
