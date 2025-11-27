<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen"
          style="background: linear-gradient(135deg, #ec4899 0%, #a855f7 50%, #3b82f6 100%);"
          x-data="{ 
              sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
              toggleSidebar() {
                  this.sidebarCollapsed = !this.sidebarCollapsed;
                  localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
              }
          }">
        <!-- Desktop Sidebar - Colapsable -->
        <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'"
               class="hidden lg:flex fixed left-0 top-0 bottom-0 flex-col border-r border-pink-200/50 bg-white shadow-lg z-30 transition-all duration-300">
            <div class="flex flex-col h-full p-4">
                <!-- Logo and Toggle -->
                <div class="flex items-center justify-between mb-6">
                    <a href="{{ route('dashboard') }}" 
                       :class="sidebarCollapsed ? 'justify-center' : 'space-x-2'"
                       class="flex items-center p-3 rounded-xl hover:bg-white/50 transition-all group" 
                       wire:navigate>
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg flex-shrink-0">
                            <span class="text-white text-xl font-bold">💑</span>
                        </div>
                        <span x-show="!sidebarCollapsed" 
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0"
                              x-transition:enter-end="opacity-100"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-start="opacity-100"
                              x-transition:leave-end="opacity-0"
                              class="text-xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Citas</span>
                    </a>
                    <button @click="toggleSidebar()" 
                            class="p-2 rounded-lg hover:bg-white/50 transition-all text-neutral-600 hover:text-pink-600 flex-shrink-0"
                            :title="sidebarCollapsed ? 'Expandir sidebar' : 'Colapsar sidebar'">
                        <svg x-show="!sidebarCollapsed" 
                             x-transition
                             class="w-5 h-5" 
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                        <svg x-show="sidebarCollapsed" 
                             x-transition
                             class="w-5 h-5" 
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-2">
                    <div class="space-y-2">
                        <div x-show="!sidebarCollapsed" 
                             x-transition
                             class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-3 mb-2">
                            {{ __('Navegación') }}
                        </div>
                        
                        <a href="{{ route('dashboard') }}" 
                           :class="sidebarCollapsed ? 'justify-center px-3' : 'gap-3 px-3'"
                           class="flex items-center py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                           wire:navigate
                           :title="sidebarCollapsed ? 'Dashboard' : ''">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span x-show="!sidebarCollapsed" 
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0"
                                  x-transition:enter-end="opacity-100"
                                  x-transition:leave="transition ease-in duration-200"
                                  x-transition:leave-start="opacity-100"
                                  x-transition:leave-end="opacity-0"
                                  class="font-medium">{{ __('Dashboard') }}</span>
                        </a>
                        
                        @auth
                            @if(auth()->user()->hasCouple())
                                <a href="{{ route('plans.index') }}" 
                                   :class="sidebarCollapsed ? 'justify-center px-3' : 'gap-3 px-3'"
                                   class="flex items-center py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all group {{ request()->routeIs('plans.index') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate
                                   :title="sidebarCollapsed ? 'Mis Planes' : ''">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-show="!sidebarCollapsed" 
                                          x-transition:enter="transition ease-out duration-200"
                                          x-transition:enter-start="opacity-0"
                                          x-transition:enter-end="opacity-100"
                                          x-transition:leave="transition ease-in duration-200"
                                          x-transition:leave-start="opacity-100"
                                          x-transition:leave-end="opacity-0"
                                          class="font-medium">{{ __('Mis Planes') }}</span>
                                </a>
                                
                                <a href="{{ route('plans.calendar') }}" 
                                   :class="sidebarCollapsed ? 'justify-center px-3' : 'gap-3 px-3'"
                                   class="flex items-center py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all group {{ request()->routeIs('plans.calendar') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate
                                   :title="sidebarCollapsed ? 'Calendario' : ''">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-show="!sidebarCollapsed" 
                                          x-transition:enter="transition ease-out duration-200"
                                          x-transition:enter-start="opacity-0"
                                          x-transition:enter-end="opacity-100"
                                          x-transition:leave="transition ease-in duration-200"
                                          x-transition:leave-start="opacity-100"
                                          x-transition:leave-end="opacity-0"
                                          class="font-medium">{{ __('Calendario') }}</span>
                                </a>
                                
                                <a href="{{ route('plans.favorites') }}" 
                                   :class="sidebarCollapsed ? 'justify-center px-3' : 'gap-3 px-3'"
                                   class="flex items-center py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all group {{ request()->routeIs('plans.favorites') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate
                                   :title="sidebarCollapsed ? 'Favoritos' : ''">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    <span x-show="!sidebarCollapsed" 
                                          x-transition:enter="transition ease-out duration-200"
                                          x-transition:enter-start="opacity-0"
                                          x-transition:enter-end="opacity-100"
                                          x-transition:leave="transition ease-in duration-200"
                                          x-transition:leave-start="opacity-100"
                                          x-transition:leave-end="opacity-0"
                                          class="font-medium">{{ __('Favoritos') }}</span>
                                </a>
                                
                                <a href="{{ route('statistics.index') }}" 
                                   :class="sidebarCollapsed ? 'justify-center px-3' : 'gap-3 px-3'"
                                   class="flex items-center py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all group {{ request()->routeIs('statistics.*') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate
                                   :title="sidebarCollapsed ? 'Estadísticas' : ''">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span x-show="!sidebarCollapsed" 
                                          x-transition:enter="transition ease-out duration-200"
                                          x-transition:enter-start="opacity-0"
                                          x-transition:enter-end="opacity-100"
                                          x-transition:leave="transition ease-in duration-200"
                                          x-transition:leave-start="opacity-100"
                                          x-transition:leave-end="opacity-0"
                                          class="font-medium">{{ __('Estadísticas') }}</span>
                                </a>
                                
                                <a href="{{ route('badges.index') }}" 
                                   :class="sidebarCollapsed ? 'justify-center px-3' : 'gap-3 px-3'"
                                   class="flex items-center py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all group {{ request()->routeIs('badges.*') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate
                                   :title="sidebarCollapsed ? 'Insignias' : ''">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    <span x-show="!sidebarCollapsed" 
                                          x-transition:enter="transition ease-out duration-200"
                                          x-transition:enter-start="opacity-0"
                                          x-transition:enter-end="opacity-100"
                                          x-transition:leave="transition ease-in duration-200"
                                          x-transition:leave-start="opacity-100"
                                          x-transition:leave-end="opacity-0"
                                          class="font-medium">{{ __('Insignias') }}</span>
                                </a>
                            @endif
                        @endauth
                    </div>

                    <div class="pt-4 mt-4 border-t border-pink-200/50 space-y-2">
                        <a href="https://github.com/laravel/livewire-starter-kit" 
                           target="_blank"
                           :class="sidebarCollapsed ? 'justify-center px-3' : 'gap-3 px-3'"
                           class="flex items-center py-2 rounded-xl hover:bg-gradient-to-r hover:from-purple-500/10 hover:to-blue-500/10 transition-all group text-neutral-700"
                           :title="sidebarCollapsed ? 'Repository' : ''">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" 
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0"
                                  x-transition:enter-end="opacity-100"
                                  x-transition:leave="transition ease-in duration-200"
                                  x-transition:leave-start="opacity-100"
                                  x-transition:leave-end="opacity-0"
                                  class="font-medium">{{ __('Repository') }}</span>
                        </a>
                        
                        <a href="https://laravel.com/docs/starter-kits#livewire" 
                           target="_blank"
                           :class="sidebarCollapsed ? 'justify-center px-3' : 'gap-3 px-3'"
                           class="flex items-center py-2 rounded-xl hover:bg-gradient-to-r hover:from-purple-500/10 hover:to-blue-500/10 transition-all group text-neutral-700"
                           :title="sidebarCollapsed ? 'Documentation' : ''">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span x-show="!sidebarCollapsed" 
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0"
                                  x-transition:enter-end="opacity-100"
                                  x-transition:leave="transition ease-in duration-200"
                                  x-transition:leave-start="opacity-100"
                                  x-transition:leave-end="opacity-0"
                                  class="font-medium">{{ __('Documentation') }}</span>
                        </a>
                    </div>
                </nav>

                <!-- User Menu -->
                <div class="pt-4 border-t border-pink-200/50">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                :class="sidebarCollapsed ? 'justify-center' : 'gap-3'"
                                class="w-full flex items-center p-3 rounded-xl hover:bg-white/50 transition-all group">
                            <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-xl shadow-md">
                                <span class="flex h-full w-full items-center justify-center bg-gradient-to-br from-pink-500 to-purple-600 text-white font-semibold">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div x-show="!sidebarCollapsed" 
                                 x-transition
                                 class="grid flex-1 text-start text-sm leading-tight min-w-0">
                                <span class="truncate font-semibold text-neutral-900 group-hover:text-pink-600 transition-colors">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs text-neutral-600">{{ auth()->user()->email }}</span>
                            </div>
                            <svg x-show="!sidebarCollapsed" 
                                 x-transition
                                 class="w-5 h-5 text-neutral-400 group-hover:text-pink-600 transition-colors flex-shrink-0" 
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open && !sidebarCollapsed" 
                             @click.away="open = false"
                             x-transition
                             class="absolute bottom-full left-0 mb-2 w-64 bg-white/95 backdrop-blur-md border border-pink-200/50 shadow-xl rounded-xl overflow-hidden z-50">
                            <div class="flex items-center gap-2 px-3 py-3 bg-gradient-to-r from-pink-50 to-purple-50">
                                <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                                    <span class="flex h-full w-full items-center justify-center bg-gradient-to-br from-pink-500 to-purple-600 text-white font-semibold">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-neutral-900">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs text-neutral-600">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                            
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" 
                                   wire:navigate
                                   class="flex items-center gap-3 px-3 py-2 text-sm text-neutral-700 hover:bg-pink-50/50 hover:text-pink-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ __('Settings') }}</span>
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-neutral-700 hover:bg-red-50/50 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>{{ __('Log Out') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar - Ya es desplegable -->
        <div x-data="{ open: false }" class="lg:hidden">
            <!-- Mobile Header -->
            <header class="bg-white/80 backdrop-blur-md border-b border-pink-200/50 shadow-sm sticky top-0 z-40">
                <div class="flex items-center justify-between px-4 h-16">
                    <button @click="open = !open" class="p-2 rounded-lg hover:bg-pink-50/50 transition-all">
                        <svg class="w-6 h-6 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2" wire:navigate>
                        <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white text-lg font-bold">💑</span>
                        </div>
                        <span class="text-lg font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Citas</span>
                    </a>

                    <div class="w-10"></div>
                </div>
            </header>

            <!-- Mobile Sidebar Overlay -->
            <div x-show="open" 
                 @click="open = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/50 z-40"></div>

            <!-- Mobile Sidebar -->
            <aside x-show="open"
                   x-transition:enter="transition ease-out duration-300 transform"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-300 transform"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full"
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl border-r border-pink-200/50">
                <div class="flex flex-col h-full p-4">
                    <div class="flex items-center justify-between mb-6">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2" wire:navigate>
                            <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                                <span class="text-white text-xl font-bold">💑</span>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Citas</span>
                        </a>
                        <button @click="open = false" class="p-2 rounded-lg hover:bg-white/50">
                            <svg class="w-6 h-6 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <nav class="flex-1 space-y-2">
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                           wire:navigate>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="font-medium">{{ __('Dashboard') }}</span>
                        </a>
                        
                        @auth
                            @if(auth()->user()->hasCouple())
                                <a href="{{ route('plans.index') }}" 
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all {{ request()->routeIs('plans.index') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium">{{ __('Mis Planes') }}</span>
                                </a>
                                
                                <a href="{{ route('plans.calendar') }}" 
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all {{ request()->routeIs('plans.calendar') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium">{{ __('Calendario') }}</span>
                                </a>
                                
                                <a href="{{ route('plans.favorites') }}" 
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all {{ request()->routeIs('plans.favorites') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    <span class="font-medium">{{ __('Favoritos') }}</span>
                                </a>
                                
                                <a href="{{ route('statistics.index') }}" 
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all {{ request()->routeIs('statistics.*') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span class="font-medium">{{ __('Estadísticas') }}</span>
                                </a>
                                
                                <a href="{{ route('badges.index') }}" 
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gradient-to-r hover:from-pink-500/10 hover:to-purple-500/10 transition-all {{ request()->routeIs('badges.*') ? 'bg-gradient-to-r from-pink-500/10 to-purple-500/10 text-pink-600' : 'text-neutral-700' }}"
                                   wire:navigate>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    <span class="font-medium">{{ __('Insignias') }}</span>
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </aside>
        </div>

        <!-- Main Content - Se ajusta dinámicamente al ancho del sidebar -->
        <div :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-64'" 
             class="transition-all duration-300 min-h-screen">
            <main class="min-h-screen">
                {{ $slot }}
            </main>
        </div>

        @fluxScripts
    </body>
</html>
