<?php

use App\Models\Couple;
use Livewire\Volt\Component;

new class extends Component
{
    public Couple $couple;

    public function mount(Couple $couple)
    {
        $this->couple = $couple;
        
        // Verify user belongs to this couple
        if (auth()->user()->couple_id !== $couple->id) {
            abort(403);
        }
    }

    public function copyCode()
    {
        // This will be handled by JavaScript
    }
}; ?>

<div>
    <x-layouts.app :title="__('Código de Pareja')">
        <div class="min-h-screen flex items-center justify-center py-12 px-4">
            <div class="max-w-2xl w-full">
                <div class="relative overflow-hidden rounded-3xl bg-white p-8 md:p-10 shadow-2xl"
                     x-data="{ 
                         show: false,
                         copied: false,
                         copyCode() {
                             const code = '{{ $couple->join_code }}';
                             navigator.clipboard.writeText(code).then(() => {
                                 this.copied = true;
                                 setTimeout(() => this.copied = false, 2000);
                             });
                         }
                     }"
                     x-init="setTimeout(() => show = true, 100)"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl mb-4 shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-3">{{ __('¡Pareja creada exitosamente!') }}</h2>
                        <p class="text-neutral-600 text-lg">
                            {{ __('Comparte este código único con tu pareja para que se una a tu pareja.') }}
                        </p>
                    </div>

                    <!-- Code Display -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-purple-700 mb-3 text-center">
                            {{ __('Código de unión') }}
                        </label>
                        <div class="relative">
                            <div class="bg-gradient-to-r from-pink-50 via-purple-50 to-blue-50 border-2 border-purple-300 rounded-2xl p-6">
                                <div class="text-center">
                                    <div class="inline-block bg-white rounded-xl px-6 py-4 shadow-lg mb-4">
                                        <code class="text-3xl md:text-4xl font-bold text-purple-700 tracking-wider font-mono">
                                            {{ $couple->join_code }}
                                        </code>
                                    </div>
                                    <button type="button"
                                            @click="copyCode()"
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold rounded-xl hover:shadow-xl transition-all transform hover:scale-105">
                                        <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <svg x-show="copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span x-text="copied ? '{{ __('¡Copiado!') }}' : '{{ __('Copiar código') }}'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-gradient-to-r from-pink-50 via-purple-50 to-blue-50 border-2 border-pink-200 rounded-2xl p-6 mb-6">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💡</span>
                            <div>
                                <p class="text-neutral-800 font-semibold mb-1">{{ __('¿Cómo funciona?') }}</p>
                                <p class="text-neutral-700 text-sm">
                                    {{ __('Tu pareja debe ir a la opción "Unirse a Pareja" e introducir este código de 12 caracteres para unirse a tu pareja.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-center gap-4 pt-4">
                        <a href="{{ route('dashboard') }}" 
                           wire:navigate
                           class="px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold rounded-xl hover:shadow-xl transition-all transform hover:scale-105">
                            {{ __('Ir al Dashboard') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-layouts.app>
</div>

