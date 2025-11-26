<?php

use Livewire\Volt\Component;

new class extends Component
{
    public function mount()
    {
        if (!auth()->user()->hasCouple()) {
            return $this->redirect(route('couple.setup'), navigate: true);
        }
    }
}; ?>

<div>
    <x-layouts.app :title="__('Dashboard')">
        <div class="space-y-6" 
             x-data="{ 
                 stats: {
                     total: {{ \App\Models\Plan::forCouple(auth()->user()->couple_id)->count() }},
                     completed: {{ \App\Models\Plan::forCouple(auth()->user()->couple_id)->completed()->count() }},
                     pending: {{ \App\Models\Plan::forCouple(auth()->user()->couple_id)->pending()->count() }}
                 }
             }">
            
            <!-- Hero Section -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-pink-500 via-purple-600 to-blue-600 p-8 md:p-12 shadow-2xl"
                 x-data="{ 
                     show: false,
                     init() {
                         setTimeout(() => this.show = true, 100);
                     }
                 }"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="text-4xl">💑</span>
                        </div>
                        <div>
                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                                {{ __('¡Hola') }}, {{ auth()->user()->name }}!
                            </h1>
                            <p class="text-white/90 text-lg">
                                {{ __('Bienvenido a tu espacio de planes en pareja') }}
                            </p>
                        </div>
                    </div>
                    
                    @if(auth()->user()->partner())
                        <div class="mt-6 flex items-center gap-3 bg-white/20 backdrop-blur-md rounded-xl p-4 border border-white/30">
                            <div class="flex -space-x-2">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center text-white font-bold shadow-lg border-2 border-white">
                                    {{ auth()->user()->initials() }}
                                </div>
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center text-white font-bold shadow-lg border-2 border-white">
                                    {{ auth()->user()->partner()->initials() }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold">{{ __('Tu pareja') }}</p>
                                <p class="text-white/80 text-sm">{{ auth()->user()->partner()->name }}</p>
                            </div>
                            <div class="px-4 py-2 bg-green-500/20 backdrop-blur-md rounded-lg border border-green-300/30">
                                <span class="text-green-100 font-medium text-sm">✨ {{ __('Activa') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 bg-yellow-500/20 backdrop-blur-md rounded-xl p-4 border border-yellow-300/30">
                            <p class="text-yellow-100 flex items-center gap-2">
                                <span class="text-xl">⏳</span>
                                <span>{{ __('Esperando a que tu pareja se una...') }}</span>
                            </p>
                        </div>
                    @endif
                </div>
                
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-400/20 rounded-full blur-2xl -ml-24 -mb-24"></div>
            </div>

            <!-- Quick Actions -->
            <div class="grid md:grid-cols-2 gap-6">
                <a href="{{ route('plans.index') }}" 
                   wire:navigate
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-pink-400 to-pink-600 p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
                   x-data="{ hover: false }"
                   @mouseenter="hover = true"
                   @mouseleave="hover = false">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-16 h-16 bg-white/30 backdrop-blur-md rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-3xl">📅</span>
                            </div>
                            <svg class="w-8 h-8 text-white/50 group-hover:text-white transition-transform group-hover:translate-x-2" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">{{ __('Mis Planes') }}</h3>
                        <p class="text-white/90">{{ __('Ver y gestionar todos tus planes') }}</p>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>

                <a href="{{ route('plans.create') }}" 
                   wire:navigate
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-400 to-blue-600 p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
                   x-data="{ hover: false }"
                   @mouseenter="hover = true"
                   @mouseleave="hover = false">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-16 h-16 bg-white/30 backdrop-blur-md rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-3xl">➕</span>
                            </div>
                            <svg class="w-8 h-8 text-white/50 group-hover:text-white transition-transform group-hover:translate-x-2" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">{{ __('Nuevo Plan') }}</h3>
                        <p class="text-white/90">{{ __('Crear un nuevo plan juntos') }}</p>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Total Plans -->
                <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-pink-500 via-pink-400 to-purple-500 p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer"
                     x-data="{ 
                         count: 0,
                         target: stats.total,
                         init() {
                             let duration = 1000;
                             let steps = 30;
                             let increment = this.target / steps;
                             let current = 0;
                             let timer = setInterval(() => {
                                 current += increment;
                                 if (current >= this.target) {
                                     this.count = this.target;
                                     clearInterval(timer);
                                 } else {
                                     this.count = Math.floor(current);
                                 }
                             }, duration / steps);
                         }
                     }">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white/30 backdrop-blur-md rounded-xl flex items-center justify-center">
                                <span class="text-2xl">📊</span>
                            </div>
                            <div class="text-4xl font-bold text-white" x-text="count"></div>
                        </div>
                        <p class="text-white/90 font-medium">{{ __('Planes Totales') }}</p>
                    </div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                </div>

                <!-- Completed Plans -->
                <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-400 via-emerald-500 to-teal-500 p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer"
                     x-data="{ 
                         count: 0,
                         target: stats.completed,
                         init() {
                             let duration = 1000;
                             let steps = 30;
                             let increment = this.target / steps;
                             let current = 0;
                             let timer = setInterval(() => {
                                 current += increment;
                                 if (current >= this.target) {
                                     this.count = this.target;
                                     clearInterval(timer);
                                 } else {
                                     this.count = Math.floor(current);
                                 }
                             }, duration / steps);
                         }
                     }">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white/30 backdrop-blur-md rounded-xl flex items-center justify-center">
                                <span class="text-2xl">✅</span>
                            </div>
                            <div class="text-4xl font-bold text-white" x-text="count"></div>
                        </div>
                        <p class="text-white/90 font-medium">{{ __('Planes Completados') }}</p>
                    </div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                </div>

                <!-- Pending Plans -->
                <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-400 via-orange-500 to-pink-500 p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer"
                     x-data="{ 
                         count: 0,
                         target: stats.pending,
                         init() {
                             let duration = 1000;
                             let steps = 30;
                             let increment = this.target / steps;
                             let current = 0;
                             let timer = setInterval(() => {
                                 current += increment;
                                 if (current >= this.target) {
                                     this.count = this.target;
                                     clearInterval(timer);
                                 } else {
                                     this.count = Math.floor(current);
                                 }
                             }, duration / steps);
                         }
                     }">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white/30 backdrop-blur-md rounded-xl flex items-center justify-center">
                                <span class="text-2xl">⏰</span>
                            </div>
                            <div class="text-4xl font-bold text-white" x-text="count"></div>
                        </div>
                        <p class="text-white/90 font-medium">{{ __('Planes Pendientes') }}</p>
                    </div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                </div>
            </div>

            <!-- Couple Info Card -->
            <div class="rounded-2xl bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 p-8 shadow-xl"
                 x-data="{ 
                     showCode: false,
                     copied: false,
                     copyCode() {
                         navigator.clipboard.writeText('{{ auth()->user()->couple->join_code }}');
                         this.copied = true;
                         setTimeout(() => this.copied = false, 2000);
                     }
                 }">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">{{ __('Información de Pareja') }}</h2>
                    <div class="px-4 py-2 bg-green-500/30 backdrop-blur-md rounded-full border border-green-300/50">
                        <span class="text-green-100 font-medium text-sm">✨ {{ __('Activa') }}</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 border border-white/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm mb-1">{{ __('Código de Unión') }}</p>
                                <code class="text-2xl font-bold text-white font-mono tracking-wider">
                                    {{ auth()->user()->couple->join_code }}
                                </code>
                            </div>
                            <button @click="copyCode()" 
                                    class="px-4 py-2 bg-white/30 hover:bg-white/40 backdrop-blur-md rounded-lg border border-white/50 transition-all transform hover:scale-105">
                                <span x-show="!copied" class="text-white font-medium">📋 {{ __('Copiar') }}</span>
                                <span x-show="copied" class="text-green-200 font-medium">✓ {{ __('Copiado!') }}</span>
                            </button>
                        </div>
                    </div>
                    
                    @if(auth()->user()->partner())
                        <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 border border-white/30">
                            <p class="text-white/80 text-sm mb-2">{{ __('Mi Pareja') }}</p>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center text-white font-bold shadow-lg">
                                    {{ auth()->user()->partner()->initials() }}
                                </div>
                                <div>
                                    <p class="text-white font-semibold text-lg">{{ auth()->user()->partner()->name }}</p>
                                    <p class="text-white/70 text-sm">{{ auth()->user()->partner()->email }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-500/30 backdrop-blur-md rounded-xl p-4 border border-yellow-300/50">
                            <p class="text-yellow-100 flex items-center gap-2">
                                <span class="text-xl">⏳</span>
                                <span>{{ __('Esperando a que tu pareja se una...') }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-layouts.app>
</div>
