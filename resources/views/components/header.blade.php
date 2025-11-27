<!-- Navigation Header -->
<nav class="fixed top-0 left-0 right-0 w-full z-50 bg-white backdrop-blur-md border-b border-neutral-200 transition-all duration-300 shadow-sm"
     x-data="{ scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 10"
     :class="scrolled ? 'shadow-lg' : ''">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                @auth
                    <!-- Mobile Menu Button - Solo visible en móvil -->
                    <button @click="$dispatch('toggle-mobile-menu')" 
                            class="lg:hidden p-2 rounded-lg hover:bg-pink-50/50 transition-all">
                        <svg class="w-6 h-6 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                @endauth
                <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:opacity-80 transition-opacity" wire:navigate>
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-xl font-bold">💑</span>
                    </div>
                    <span class="text-xl font-bold text-neutral-900">Citas</span>
                </a>
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
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-neutral-900 transition-colors">
                        {{ __('Iniciar Sesión') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg text-sm font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            {{ __('Registrarse') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>

