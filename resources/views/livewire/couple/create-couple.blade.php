<?php

use App\Actions\Couple\CreateCoupleAction;
use App\Models\Couple;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';

    public function createCouple()
    {
        $this->authorize('create', Couple::class);

        $validated = $this->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => __('El nombre de la pareja es obligatorio.'),
            'name.max' => __('El nombre no puede exceder 255 caracteres.'),
        ]);

        try {
            $couple = app(CreateCoupleAction::class)->execute(
                auth()->user(),
                $validated['name']
            );

            session()->flash('success', __('Pareja creada exitosamente. Comparte este código con tu pareja: :code', ['code' => $couple->join_code]));
            
            return $this->redirect(route('dashboard'), navigate: true);
        } catch (\Exception $e) {
            $this->addError('couple', $e->getMessage());
        }
    }
}; ?>

<div>
    <x-layouts.app :title="__('Crear Pareja')">
        <div class="min-h-screen flex items-center justify-center py-12 px-4">
            <div class="max-w-2xl w-full">
                <div class="relative overflow-hidden rounded-3xl bg-white p-8 md:p-10 shadow-2xl"
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
                    
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl mb-4 shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-3">{{ __('Crear una nueva pareja') }}</h2>
                        <p class="text-neutral-600 text-lg">
                            {{ __('Crea una nueva pareja y obtendrás un código único para compartir con tu pareja.') }}
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mb-6 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-green-800 font-semibold mb-1">{{ __('¡Éxito!') }}</p>
                                    <p class="text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @error('couple')
                        <div class="mb-6 p-5 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-200 rounded-2xl">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-red-800 font-semibold mb-1">{{ __('Error') }}</p>
                                    <p class="text-red-700">{{ $message }}</p>
                                </div>
                            </div>
                        </div>
                    @enderror

                    <form wire:submit="createCouple" class="space-y-6">
                        <!-- Nombre de la pareja -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-purple-700 mb-2">
                                {{ __('Nombre de la pareja') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   wire:model="name"
                                   required
                                   placeholder="{{ __('Ej: Mi Pareja Perfecta') }}"
                                   class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gradient-to-r from-pink-50 via-purple-50 to-blue-50 border-2 border-pink-200 rounded-2xl p-6">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">💡</span>
                                <div>
                                    <p class="text-neutral-800 font-semibold mb-1">{{ __('¿Cómo funciona?') }}</p>
                                    <p class="text-neutral-700 text-sm">
                                        {{ __('Al crear una pareja, se generará un código único de 12 caracteres. Comparte este código con tu pareja para que se una a tu pareja.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('couple.setup') }}" 
                               wire:navigate
                               class="px-6 py-3 text-neutral-700 hover:text-neutral-900 font-semibold rounded-xl hover:bg-neutral-100 transition-all">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold rounded-xl hover:shadow-xl transition-all transform hover:scale-105">
                                <span wire:loading.remove wire:target="createCouple">{{ __('Crear Pareja') }}</span>
                                <span wire:loading wire:target="createCouple" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('Creando...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-layouts.app>
</div>
