@php
    $isHomePage = request()->routeIs('home');
@endphp

<!-- Navigation Header -->
<nav class="fixed top-0 left-0 right-0 w-full z-50 backdrop-blur-md border-b transition-all duration-300 shadow-sm {{ $isHomePage ? 'bg-gradient-to-r from-pink-500 via-purple-600 to-blue-600 border-white/20' : 'bg-white border-neutral-200' }}"
     x-data="{ scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 10"
     :class="scrolled ? 'shadow-lg' : ''">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                @auth
                    <!-- Mobile Menu Button - Solo visible en móvil -->
                    <button @click="$dispatch('toggle-mobile-menu')" 
                            class="lg:hidden p-2 rounded-lg transition-all {{ $isHomePage ? 'hover:bg-white/20 text-white' : 'hover:bg-pink-50/50 text-neutral-700' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                @endauth
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg text-sm font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            {{ __('Cerrar Sesión') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium {{ $isHomePage ? 'text-white hover:text-white/80' : 'text-neutral-700 hover:text-neutral-900' }} transition-colors">
                        {{ __('Iniciar Sesión') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 {{ $isHomePage ? 'bg-white text-pink-600 hover:bg-white/90' : 'bg-gradient-to-r from-pink-500 to-purple-600 text-white' }} rounded-lg text-sm font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            {{ __('Registrarse') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>

