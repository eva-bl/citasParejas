<?php

use App\Actions\Couple\CreateCoupleAction;
use App\Models\Couple;
use Livewire\Volt\Component;

new class extends Component
{
    public function createCouple()
    {
        $this->authorize('create', Couple::class);

        try {
            $couple = app(CreateCoupleAction::class)->execute(auth()->user());

            session()->flash('success', 'Pareja creada exitosamente. Comparte este código con tu pareja: ' . $couple->join_code);
            
            return $this->redirect(route('dashboard'), navigate: true);
        } catch (\Exception $e) {
            $this->addError('couple', $e->getMessage());
        }
    }
}; ?>

<div>
    <x-layouts.app :title="__('Crear Pareja')">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-2xl font-bold mb-4">{{ __('Crear una nueva pareja') }}</h2>
                <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                    {{ __('Crea una nueva pareja y obtendrás un código único para compartir con tu pareja.') }}
                </p>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @error('couple')
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 rounded">
                        {{ $message }}
                    </div>
                @enderror

                <form wire:submit="createCouple" class="space-y-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <strong>{{ __('Nota:') }}</strong> {{ __('Al crear una pareja, se generará un código único que deberás compartir con tu pareja para que se una.') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <flux:link href="{{ route('couple.setup') }}" wire:navigate>
                            {{ __('Cancelar') }}
                        </flux:link>
                        <flux:button type="submit" variant="primary">
                            {{ __('Crear Pareja') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </x-layouts.app>
</div>

