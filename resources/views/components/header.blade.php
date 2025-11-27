@php
    $isHomePage = request()->routeIs('home');
@endphp

<!-- Navigation Header -->
<nav class="fixed top-0 left-0 right-0 w-full z-50 backdrop-blur-md border-b transition-all duration-300 shadow-sm {{ $isHomePage ? 'bg-gradient-to-r from-pink-500 via-purple-600 to-blue-600 border-white/20' : 'bg-white border-neutral-200' }}"
     x-data="{ scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 10"
     :class="scrolled ? 'shadow-lg' : ''">
    @auth
        <!-- Desktop Sidebar Toggle Button - Fuera del contenedor, a la izquierda -->
        <button @click="$dispatch('toggle-sidebar')" 
                class="fixed left-4 top-4 z-50 hidden lg:flex p-2 rounded-lg bg-gradient-to-r from-pink-500 via-purple-600 to-blue-600 text-white transition-all hover:shadow-lg transform hover:scale-110"
                title="{{ __('Abrir menú') }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <!-- Mobile Menu Button - Fuera del contenedor, a la izquierda -->
        <button @click="$dispatch('toggle-mobile-menu')" 
                class="fixed left-4 top-4 z-50 lg:hidden p-2 rounded-lg bg-gradient-to-r from-pink-500 via-purple-600 to-blue-600 text-white transition-all hover:shadow-lg transform hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    @endauth
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Menu -->
                    <div x-data="{ open: false }" 
                         @click.away="open = false"
                         class="relative">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all {{ $isHomePage ? 'hover:bg-white/20 text-white' : 'hover:bg-pink-50 text-neutral-700' }}">
                            <span class="text-sm font-medium {{ $isHomePage ? 'text-white' : 'text-neutral-700' }}">{{ auth()->user()->name }}</span>
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center bg-gradient-to-br from-pink-500 to-purple-600 text-white text-xs font-semibold">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                <a href="{{ route('profile.edit') }}" 
                                   wire:navigate
                                   class="flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-pink-50 hover:text-pink-600 transition-colors"
                                   role="menuitem">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ __('Mi Perfil') }}</span>
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-red-50 hover:text-red-600 transition-colors text-left"
                                            role="menuitem">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>{{ __('Cerrar Sesión') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
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

