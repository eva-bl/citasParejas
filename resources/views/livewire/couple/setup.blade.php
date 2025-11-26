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
                <div class="text-center mb-12"
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
                <div class="grid md:grid-cols-2 gap-8"
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
                    <a href="{{ route('couple.create') }}" 
                       wire:navigate
                       class="group relative overflow-hidden rounded-3xl bg-white p-10 shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-2"
                       x-data="{ hover: false }"
                       @mouseenter="hover = true"
                       @mouseleave="hover = false">
                        <!-- Decorative gradient overlay on hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-500/10 via-purple-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-20 h-20 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-xl transform group-hover:rotate-6 transition-transform duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <svg class="w-8 h-8 text-pink-300 group-hover:text-pink-500 transition-transform group-hover:translate-x-2 duration-300" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                            
                            <h3 class="text-3xl font-bold text-neutral-900 mb-3 group-hover:text-pink-600 transition-colors">
                                {{ __('Crear Pareja') }}
                            </h3>
                            
                            <p class="text-neutral-600 text-lg mb-6 leading-relaxed">
                                {{ __('Crea una nueva pareja y obtén un código único para compartir con tu pareja.') }}
                            </p>
                            
                            <div class="flex items-center gap-2 text-pink-600 font-semibold">
                                <span>{{ __('Empezar') }}</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-pink-100 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-purple-100 rounded-full blur-2xl -ml-12 -mb-12 opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                    </a>

                    <!-- Unirse a Pareja -->
                    <a href="{{ route('couple.join') }}" 
                       wire:navigate
                       class="group relative overflow-hidden rounded-3xl bg-white p-10 shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-2"
                       x-data="{ hover: false }"
                       @mouseenter="hover = true"
                       @mouseleave="hover = false">
                        <!-- Decorative gradient overlay on hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-blue-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-xl transform group-hover:rotate-6 transition-transform duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                                <svg class="w-8 h-8 text-purple-300 group-hover:text-purple-500 transition-transform group-hover:translate-x-2 duration-300" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                            
                            <h3 class="text-3xl font-bold text-neutral-900 mb-3 group-hover:text-purple-600 transition-colors">
                                {{ __('Unirse a Pareja') }}
                            </h3>
                            
                            <p class="text-neutral-600 text-lg mb-6 leading-relaxed">
                                {{ __('Únete a una pareja existente usando el código que tu pareja te compartió.') }}
                            </p>
                            
                            <div class="flex items-center gap-2 text-purple-600 font-semibold">
                                <span>{{ __('Empezar') }}</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-100 rounded-full blur-2xl -ml-12 -mb-12 opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                    </a>
                </div>

                <!-- Help Text -->
                <div class="mt-12 text-center"
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
