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
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-2xl font-bold mb-4">{{ __('Unirse a una pareja existente') }}</h2>
                <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                    {{ __('Ingresa el código de unión que tu pareja te compartió.') }}
                </p>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="joinCouple" class="space-y-4">
                    <flux:input
                        wire:model="joinCode"
                        :label="__('Código de Unión')"
                        :placeholder="__('Ingresa el código de 12 caracteres')"
                        maxlength="12"
                        class="uppercase"
                        autofocus
                    />

                    @error('joinCode')
                        <div class="text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                    @enderror

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                            <strong>{{ __('¿No tienes el código?') }}</strong> {{ __('Pídele a tu pareja que cree una pareja y te comparta el código generado.') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <flux:link href="{{ route('couple.setup') }}" wire:navigate>
                            {{ __('Cancelar') }}
                        </flux:link>
                        <flux:button type="submit" variant="primary">
                            {{ __('Unirse a Pareja') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </x-layouts.app>
</div>

