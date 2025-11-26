<?php

use Livewire\Volt\Component;

new class extends Component
{
    //
}; ?>

<div>
    <x-layouts.app :title="__('Configurar Pareja')">
        <div class="min-h-screen flex items-center justify-center py-12 px-4">
            <div class="max-w-5xl w-full">
                <!-- Hero Section -->
                <div class="text-center mb-16"
                     x-data="{ 
                         show: false,
                         init() {
                             setTimeout(() => this.show = true, 100);
                         }
                     }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-md rounded-3xl mb-6 shadow-2xl">
                        <span class="text-6xl">💑</span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">
                        {{ __('¡Bienvenido!') }}
                    </h1>
                    <p class="text-xl text-white/90 max-w-2xl mx-auto">
                        {{ __('Para comenzar a usar la aplicación, necesitas crear o unirte a una pareja.') }}
                    </p>
                </div>

                <!-- Options Grid -->
                <div class="grid md:grid-cols-2 gap-12 max-w-4xl mx-auto"
                     x-data="{ 
                         show: false,
                         init() {
                             setTimeout(() => this.show = true, 300);
                         }
                     }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-1000 delay-200"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <!-- Crear Pareja -->
                    <div class="flex flex-col items-center text-center">
                        <h3 class="text-2xl md:text-3xl font-bold text-white mb-6">
                            {{ __('Crear Pareja') }}
                        </h3>
                        
                        <a href="{{ route('couple.create') }}" 
                           wire:navigate
                           class="group relative w-48 h-48 rounded-full bg-white shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-110 hover:-translate-y-2 flex items-center justify-center cursor-pointer mb-6"
                           x-data="{ hover: false }"
                           @mouseenter="hover = true"
                           @mouseleave="hover = false"
                           style="mask-image: radial-gradient(circle, transparent 0%, transparent 35%, white 35%, white 100%); -webkit-mask-image: radial-gradient(circle, transparent 0%, transparent 35%, white 35%, white 100%);">
                            <!-- Icono transparente - se ve el fondo degradado a través -->
                            <div class="absolute w-32 h-32 flex items-center justify-center">
                                <svg class="w-32 h-32" 
                                     viewBox="0 0 24 24"
                                     fill="none">
                                    <path d="M12 4v16m8-8H4" 
                                          stroke="transparent" 
                                          stroke-width="3" 
                                          stroke-linecap="round" 
                                          stroke-linejoin="round"/>
                                </svg>
                            </div>
                            
                            <!-- Efecto de brillo al hover -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-pink-500/20 via-purple-500/20 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </a>
                        
                        <p class="text-white/90 text-lg leading-relaxed max-w-xs">
                            {{ __('Crea una nueva pareja y obtén un código único para compartir con tu pareja.') }}
                        </p>
                    </div>

                    <!-- Unirse a Pareja -->
                    <div class="flex flex-col items-center text-center">
                        <h3 class="text-2xl md:text-3xl font-bold text-white mb-6">
                            {{ __('Unirse a Pareja') }}
                        </h3>
                        
                        <a href="{{ route('couple.join') }}" 
                           wire:navigate
                           class="group relative w-48 h-48 rounded-full bg-white shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-110 hover:-translate-y-2 flex items-center justify-center cursor-pointer mb-6"
                           x-data="{ hover: false }"
                           @mouseenter="hover = true"
                           @mouseleave="hover = false"
                           style="mask-image: radial-gradient(circle, transparent 0%, transparent 35%, white 35%, white 100%); -webkit-mask-image: radial-gradient(circle, transparent 0%, transparent 35%, white 35%, white 100%);">
                            <!-- Icono transparente - se ve el fondo degradado a través -->
                            <div class="absolute w-28 h-28 flex items-center justify-center">
                                <svg class="w-28 h-28" 
                                     viewBox="0 0 24 24"
                                     fill="none">
                                    <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" 
                                          stroke="transparent" 
                                          stroke-width="2" 
                                          stroke-linecap="round" 
                                          stroke-linejoin="round"/>
                                </svg>
                            </div>
                            
                            <!-- Efecto de brillo al hover -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-purple-500/20 via-blue-500/20 to-pink-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </a>
                        
                        <p class="text-white/90 text-lg leading-relaxed max-w-xs">
                            {{ __('Únete a una pareja existente usando el código que tu pareja te compartió.') }}
                        </p>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="mt-16 text-center"
                     x-data="{ 
                         show: false,
                         init() {
                             setTimeout(() => this.show = true, 500);
                         }
                     }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-1000 delay-400"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <div class="inline-flex items-center gap-3 bg-white/20 backdrop-blur-md rounded-2xl px-6 py-4 border border-white/30">
                        <span class="text-2xl">💡</span>
                        <p class="text-white/90 text-sm md:text-base">
                            <strong>{{ __('¿Primera vez?') }}</strong> {{ __('Si eres el primero en crear la pareja, elige "Crear Pareja". Si tu pareja ya creó una, elige "Unirse a Pareja".') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-layouts.app>
</div>
