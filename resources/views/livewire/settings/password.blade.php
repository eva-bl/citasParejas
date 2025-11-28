<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full min-h-screen py-8">
    <x-settings.layout>
        <!-- Card Container -->
        <div class="max-w-3xl mx-auto bg-white/60 backdrop-blur-xl shadow-lg rounded-2xl p-8 space-y-6">
            <form method="POST" wire:submit="updatePassword" class="w-full space-y-6">
                <!-- Sección: Cambiar contraseña -->
                <div>
                    <h2 class="text-lg font-semibold text-purple-700 mb-4">{{ __('Cambiar contraseña') }}</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-purple-600 mb-2">
                        {{ __('Contraseña actual') }}
                    </label>
                    <input wire:model="current_password"
                           type="password"
                           required
                           autocomplete="current-password"
                           class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-purple-600 mb-2">
                        {{ __('Nueva contraseña') }}
                    </label>
                    <input wire:model="password"
                           type="password"
                           required
                           autocomplete="new-password"
                           class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-purple-600 mb-2">
                        {{ __('Confirmar contraseña') }}
                    </label>
                    <input wire:model="password_confirmation"
                           type="password"
                           required
                           autocomplete="new-password"
                           class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700">
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                            data-test="update-password-button">
                        {{ __('Guardar') }}
                    </button>

                    <x-action-message class="me-3 text-purple-600 font-medium" on="password-updated">
                        {{ __('Guardado.') }}
                    </x-action-message>
                </div>
            </form>
        </div>
    </x-settings.layout>
</section>
