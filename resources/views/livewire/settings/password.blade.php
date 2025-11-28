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
     * Get password validation errors
     */
    public function getPasswordValidationErrorsProperty(): array
    {
        $errors = [];
        
        if (empty($this->password)) {
            return $errors;
        }
        
        $password = $this->password;
        $user = Auth::user();
        
        // 1. Mínimo 8 caracteres
        if (strlen($password) < 8) {
            $errors[] = __('Mínimo 8 caracteres');
        }
        
        // 2. Al menos una mayúscula (A-Z)
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = __('Al menos una mayúscula (A-Z)');
        }
        
        // 3. Al menos una minúscula (a-z)
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = __('Al menos una minúscula (a-z)');
        }
        
        // 4. Al menos un número (0-9)
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = __('Al menos un número (0-9)');
        }
        
        // 5. Al menos un carácter especial
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = __('Al menos un carácter especial');
        }
        
        // 6. Mínimo 12 caracteres (nivel alto) - recomendado
        if (strlen($password) < 12) {
            $errors[] = __('Recomendado: mínimo 12 caracteres para mayor seguridad');
        }
        
        // 7. No espacios al inicio o final
        if (trim($password) !== $password) {
            $errors[] = __('No puede tener espacios al inicio o final');
        }
        
        // 8. No puede contener parte del nombre del usuario
        if ($user && !empty($user->name)) {
            $nameParts = explode(' ', strtolower($user->name));
            foreach ($nameParts as $part) {
                if (strlen($part) >= 3 && stripos(strtolower($password), strtolower($part)) !== false) {
                    $errors[] = __('No puede contener parte de tu nombre de usuario');
                    break;
                }
            }
        }
        
        // 9. No más del 40% del texto repetido
        $charCounts = array_count_values(str_split($password));
        $maxRepeated = max($charCounts);
        $repeatedPercentage = ($maxRepeated / strlen($password)) * 100;
        if ($repeatedPercentage > 40) {
            $errors[] = __('No se permite más del 40% de caracteres repetidos');
        }
        
        // 10. No secuencias obvias (123, abc, qwerty, etc.)
        $sequences = [
            '0123456789',
            '9876543210',
            'abcdefghijklmnopqrstuvwxyz',
            'zyxwvutsrqponmlkjihgfedcba',
            'qwertyuiop',
            'asdfghjkl',
            'zxcvbnm',
        ];
        
        $passwordLower = strtolower($password);
        foreach ($sequences as $sequence) {
            if (strlen($password) >= 3) {
                for ($i = 0; $i <= strlen($sequence) - 3; $i++) {
                    $subsequence = substr($sequence, $i, 3);
                    if (strpos($passwordLower, $subsequence) !== false) {
                        $errors[] = __('No se permiten secuencias obvias (123, abc, qwerty, etc.)');
                        break 2;
                    }
                }
            }
        }
        
        return $errors;
    }

    /**
     * Check if password meets all requirements
     */
    public function getPasswordIsValidProperty(): bool
    {
        return empty($this->passwordValidationErrors);
    }

    /**
     * Get password validation message
     */
    public function getPasswordValidationMessageProperty(): ?string
    {
        if (empty($this->password)) {
            return null;
        }
        
        $errors = $this->passwordValidationErrors;
        
        if (empty($errors)) {
            return __('Contraseña válida y segura.');
        }
        
        // Mostrar el primer error o un resumen
        return $errors[0];
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
        $user = Auth::user();
        
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    function ($attribute, $value, $fail) use ($user) {
                        // 2. Al menos una mayúscula
                        if (!preg_match('/[A-Z]/', $value)) {
                            $fail(__('La contraseña debe contener al menos una mayúscula (A-Z).'));
                        }
                        
                        // 3. Al menos una minúscula
                        if (!preg_match('/[a-z]/', $value)) {
                            $fail(__('La contraseña debe contener al menos una minúscula (a-z).'));
                        }
                        
                        // 4. Al menos un número
                        if (!preg_match('/[0-9]/', $value)) {
                            $fail(__('La contraseña debe contener al menos un número (0-9).'));
                        }
                        
                        // 5. Al menos un carácter especial
                        if (!preg_match('/[^a-zA-Z0-9]/', $value)) {
                            $fail(__('La contraseña debe contener al menos un carácter especial.'));
                        }
                        
                        // 7. No espacios al inicio o final
                        if (trim($value) !== $value) {
                            $fail(__('La contraseña no puede tener espacios al inicio o final.'));
                        }
                        
                        // 8. No puede contener parte del nombre del usuario
                        if ($user && !empty($user->name)) {
                            $nameParts = explode(' ', strtolower($user->name));
                            foreach ($nameParts as $part) {
                                if (strlen($part) >= 3 && stripos(strtolower($value), strtolower($part)) !== false) {
                                    $fail(__('La contraseña no puede contener parte de tu nombre de usuario.'));
                                    break;
                                }
                            }
                        }
                        
                        // 9. No más del 40% del texto repetido
                        $charCounts = array_count_values(str_split($value));
                        $maxRepeated = max($charCounts);
                        $repeatedPercentage = ($maxRepeated / strlen($value)) * 100;
                        if ($repeatedPercentage > 40) {
                            $fail(__('La contraseña no puede tener más del 40% de caracteres repetidos.'));
                        }
                        
                        // 10. No secuencias obvias
                        $sequences = [
                            '0123456789',
                            '9876543210',
                            'abcdefghijklmnopqrstuvwxyz',
                            'zyxwvutsrqponmlkjihgfedcba',
                            'qwertyuiop',
                            'asdfghjkl',
                            'zxcvbnm',
                        ];
                        
                        $passwordLower = strtolower($value);
                        foreach ($sequences as $sequence) {
                            if (strlen($value) >= 3) {
                                for ($i = 0; $i <= strlen($sequence) - 3; $i++) {
                                    $subsequence = substr($sequence, $i, 3);
                                    if (strpos($passwordLower, $subsequence) !== false) {
                                        $fail(__('La contraseña no puede contener secuencias obvias (123, abc, qwerty, etc.).'));
                                        break 2;
                                    }
                                }
                            }
                        }
                    },
                ],
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
        <div class="max-w-xl mx-auto bg-white/70 backdrop-blur-xl shadow-xl rounded-2xl p-10">
            <form method="POST" wire:submit="updatePassword" class="w-full space-y-6">
                <!-- Sección: Cambiar contraseña -->
                <div>
                    <h2 class="text-2xl font-semibold text-purple-700 mb-6">{{ __('Cambiar contraseña') }}</h2>
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
                    @if(!empty($this->password))
                        <div class="mt-2 space-y-1">
                            @if($this->passwordIsValid)
                                <p class="text-xs text-green-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>{{ __('Contraseña válida y segura.') }}</span>
                                </p>
                            @else
                                <p class="text-xs text-red-600 font-medium mb-2">{{ __('La contraseña debe cumplir los siguientes requisitos:') }}</p>
                                <ul class="space-y-1 text-xs text-red-600">
                                    @php
                                        $password = $this->password;
                                        $user = Auth::user();
                                        $checks = [
                                            'Mínimo 8 caracteres' => strlen($password) >= 8,
                                            'Al menos una mayúscula (A-Z)' => preg_match('/[A-Z]/', $password),
                                            'Al menos una minúscula (a-z)' => preg_match('/[a-z]/', $password),
                                            'Al menos un número (0-9)' => preg_match('/[0-9]/', $password),
                                            'Al menos un carácter especial' => preg_match('/[^a-zA-Z0-9]/', $password),
                                            'Recomendado: mínimo 12 caracteres' => strlen($password) >= 12,
                                            'Sin espacios al inicio o final' => trim($password) === $password,
                                        ];
                                        
                                        // Validar nombre de usuario
                                        $nameCheck = true;
                                        if ($user && !empty($user->name)) {
                                            $nameParts = explode(' ', strtolower($user->name));
                                            foreach ($nameParts as $part) {
                                                if (strlen($part) >= 3 && stripos(strtolower($password), strtolower($part)) !== false) {
                                                    $nameCheck = false;
                                                    break;
                                                }
                                            }
                                        }
                                        $checks['No contiene parte de tu nombre'] = $nameCheck;
                                        
                                        // Validar caracteres repetidos
                                        $charCounts = array_count_values(str_split($password));
                                        $maxRepeated = max($charCounts);
                                        $repeatedPercentage = strlen($password) > 0 ? ($maxRepeated / strlen($password)) * 100 : 0;
                                        $checks['No más del 40% de caracteres repetidos'] = $repeatedPercentage <= 40;
                                        
                                        // Validar secuencias
                                        $sequences = [
                                            '0123456789',
                                            '9876543210',
                                            'abcdefghijklmnopqrstuvwxyz',
                                            'zyxwvutsrqponmlkjihgfedcba',
                                            'qwertyuiop',
                                            'asdfghjkl',
                                            'zxcvbnm',
                                        ];
                                        $sequenceCheck = true;
                                        $passwordLower = strtolower($password);
                                        foreach ($sequences as $sequence) {
                                            if (strlen($password) >= 3) {
                                                for ($i = 0; $i <= strlen($sequence) - 3; $i++) {
                                                    $subsequence = substr($sequence, $i, 3);
                                                    if (strpos($passwordLower, $subsequence) !== false) {
                                                        $sequenceCheck = false;
                                                        break 2;
                                                    }
                                                }
                                            }
                                        }
                                        $checks['No contiene secuencias obvias'] = $sequenceCheck;
                                    @endphp
                                    @foreach($checks as $label => $isValid)
                                        <li class="flex items-center gap-2">
                                            @if($isValid)
                                                <svg class="w-3 h-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-green-600">{{ $label }}</span>
                                            @else
                                                <svg class="w-3 h-3 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span class="text-red-600">{{ $label }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
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
