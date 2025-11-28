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
     * Check if password meets minimum requirements
     */
    public function getPasswordIsValidProperty(): bool
    {
        if (empty($this->password)) {
            return false;
        }
        
        // Minimum 8 characters
        if (strlen($this->password) < 8) {
            return false;
        }
        
        return true;
    }

    /**
     * Get password validation message
     */
    public function getPasswordValidationMessageProperty(): ?string
    {
        if (empty($this->password)) {
            return null;
        }
        
        if (strlen($this->password) < 8) {
            return __('La contraseña debe tener al menos 8 caracteres.');
        }
        
        return __('Contraseña válida.');
    }

    /**
     * Check if password confirmation matches
     */
    public function getPasswordConfirmationMatchesProperty(): bool
    {
        if (empty($this->password_confirmation) || empty($this->password)) {
            return false;
        }
        
        return $this->password === $this->password_confirmation;
    }

    /**
     * Get password confirmation validation message
     */
    public function getPasswordConfirmationMessageProperty(): ?string
    {
        if (empty($this->password_confirmation)) {
            return null;
        }
        
        if (empty($this->password)) {
            return null;
        }
        
        if ($this->password === $this->password_confirmation) {
            return __('Las contraseñas coinciden.');
        }
        
        return __('Las contraseñas no coinciden.');
    }

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
                    <div class="relative">
                        <input wire:model.live="password"
                               type="password"
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-2 pr-10 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 {{ $this->passwordIsValid && !empty($this->password) ? 'border-green-500' : ($this->password && !$this->passwordIsValid ? 'border-red-500' : 'border-neutral-300') }}">
                        @if(!empty($this->password))
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                @if($this->passwordIsValid)
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </div>
                        @endif
                    </div>
                    @if($this->passwordValidationMessage)
                        <p class="mt-1 text-xs {{ $this->passwordIsValid ? 'text-green-600' : 'text-red-600' }} flex items-center gap-1">
                            @if($this->passwordIsValid)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                            <span>{{ $this->passwordValidationMessage }}</span>
                        </p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-purple-600 mb-2">
                        {{ __('Confirmar contraseña') }}
                    </label>
                    <div class="relative">
                        <input wire:model.live="password_confirmation"
                               type="password"
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-2 pr-10 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 {{ $this->passwordConfirmationMatches && !empty($this->password_confirmation) ? 'border-green-500' : ($this->password_confirmation && !$this->passwordConfirmationMatches ? 'border-red-500' : 'border-neutral-300') }}">
                        @if(!empty($this->password_confirmation) && !empty($this->password))
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                @if($this->passwordConfirmationMatches)
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </div>
                        @endif
                    </div>
                    @if($this->passwordConfirmationMessage)
                        <p class="mt-1 text-xs {{ $this->passwordConfirmationMatches ? 'text-green-600' : 'text-red-600' }} flex items-center gap-1">
                            @if($this->passwordConfirmationMatches)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                            <span>{{ $this->passwordConfirmationMessage }}</span>
                        </p>
                    @endif
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
