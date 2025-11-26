<?php

use Livewire\Volt\Component;

new class extends Component
{
    //
}; ?>

<div>
    <x-layouts.app :title="__('Configurar Pareja')">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">{{ __('¡Bienvenido!') }}</h1>
                <p class="text-neutral-600 dark:text-neutral-400">
                    {{ __('Para comenzar a usar la aplicación, necesitas crear o unirte a una pareja.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Crear Pareja -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 dark:bg-blue-900/20 rounded-full mb-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Crear Pareja') }}</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                            {{ __('Crea una nueva pareja y obtén un código único para compartir.') }}
                        </p>
                    </div>
                    <flux:link href="{{ route('couple.create') }}" wire:navigate variant="primary" class="w-full justify-center">
                        {{ __('Crear Pareja') }}
                    </flux:link>
                </div>

                <!-- Unirse a Pareja -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900/20 rounded-full mb-4">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Unirse a Pareja') }}</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                            {{ __('Únete a una pareja existente usando el código que te compartieron.') }}
                        </p>
                    </div>
                    <flux:link href="{{ route('couple.join') }}" wire:navigate variant="primary" class="w-full justify-center">
                        {{ __('Unirse a Pareja') }}
                    </flux:link>
                </div>
            </div>
        </div>
    </x-layouts.app>
</div>


