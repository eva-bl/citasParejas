<?php

use App\Actions\Couple\JoinCoupleAction;
use Livewire\Volt\Component;

new class extends Component
{
    public string $joinCode = '';

    protected function rules(): array
    {
        return [
            'joinCode' => ['required', 'string', 'size:12', 'regex:/^[A-Z0-9]+$/'],
        ];
    }

    protected function messages(): array
    {
        return [
            'joinCode.required' => 'El código de unión es obligatorio.',
            'joinCode.size' => 'El código debe tener 12 caracteres.',
            'joinCode.regex' => 'El código solo puede contener letras mayúsculas y números.',
        ];
    }

    public function joinCouple()
    {
        $this->validate();

        try {
            $couple = app(JoinCoupleAction::class)->execute(
                auth()->user(),
                strtoupper($this->joinCode)
            );

            session()->flash('success', 'Te has unido exitosamente a la pareja.');
            
            return $this->redirect(route('dashboard'), navigate: true);
        } catch (\Exception $e) {
            $this->addError('joinCode', $e->getMessage());
        }
    }
}; ?>

<div>
    <x-layouts.app :title="__('Unirse a Pareja')">
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
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-400 to-blue-600 rounded-2xl mb-4 shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-3">{{ __('Unirse a una pareja existente') }}</h2>
                        <p class="text-neutral-600 text-lg">
                            {{ __('Ingresa el código de unión que tu pareja te compartió.') }}
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

                    <form wire:submit="joinCouple" class="space-y-6">
                        <div>
                            <label for="joinCode" class="block text-sm font-semibold text-neutral-900 mb-2">
                                {{ __('Código de Unión') }}
                            </label>
                            <input type="text"
                                   id="joinCode"
                                   wire:model="joinCode"
                                   maxlength="12"
                                   autofocus
                                   class="w-full px-6 py-4 text-2xl font-bold text-center tracking-widest uppercase border-2 border-neutral-200 rounded-2xl focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all"
                                   placeholder="ABCD1234EFGH">
                            
                            @error('joinCode')
                                <div class="mt-3 p-4 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-200 rounded-xl">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        <p class="text-red-800 text-sm">{{ $message }}</p>
                                    </div>
                                </div>
                            @enderror
                        </div>

                        <div class="bg-gradient-to-r from-yellow-50 via-orange-50 to-pink-50 border-2 border-yellow-200 rounded-2xl p-6">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">💡</span>
                                <div>
                                    <p class="text-neutral-800 font-semibold mb-1">{{ __('¿No tienes el código?') }}</p>
                                    <p class="text-neutral-700 text-sm">
                                        {{ __('Pídele a tu pareja que cree una pareja y te comparta el código generado de 12 caracteres.') }}
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
                                    class="px-8 py-3 bg-gradient-to-r from-purple-500 to-blue-600 text-white font-semibold rounded-xl hover:shadow-xl transition-all transform hover:scale-105">
                                <span wire:loading.remove wire:target="joinCouple">{{ __('Unirse a Pareja') }}</span>
                                <span wire:loading wire:target="joinCouple" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('Uniéndose...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-layouts.app>
</div>
