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
     * Check if password has minimum 8 characters
     */
    public function getHasMinLengthProperty(): bool
    {
        return !empty($this->password) && strlen($this->password) >= 8;
    }

    /**
     * Check if password has at least one number
     */
    public function getHasNumberProperty(): bool
    {
        return !empty($this->password) && preg_match('/[0-9]/', $this->password);
    }

    /**
     * Check if password has at least one uppercase letter
     */
    public function getHasUppercaseProperty(): bool
    {
        return !empty($this->password) && preg_match('/[A-Z]/', $this->password);
    }

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
                    <div class="relative" x-data="{ show: false }">
                        <input wire:model="current_password"
                               :type="show ? 'text' : 'password'"
                               required
                               autocomplete="current-password"
                               class="w-full px-4 py-2 pr-10 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700">
                        <button type="button"
                                @click="show = !show"
                                class="absolute right-3 top-2.5 cursor-pointer text-purple-500 hover:text-purple-700 transition-colors"
                                title="{{ __('Mostrar/Ocultar contraseña') }}">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-purple-600 mb-2">
                        {{ __('Nueva contraseña') }}
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <input wire:model.live="password"
                               :type="show ? 'text' : 'password'"
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-2 pr-10 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 {{ $this->passwordIsValid && !empty($this->password) ? 'border-green-500' : ($this->password && !$this->passwordIsValid ? 'border-red-500' : 'border-neutral-300') }}">
                        <div class="absolute right-3 top-2.5 flex items-center gap-2">
                            @if(!empty($this->password))
                                <div>
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
                            <button type="button"
                                    @click="show = !show"
                                    class="cursor-pointer text-purple-500 hover:text-purple-700 transition-colors"
                                    title="{{ __('Mostrar/Ocultar contraseña') }}">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Lista visual de requisitos básicos -->
                    @if(!empty($this->password))
                        <div class="mt-3 space-y-1">
                            <p class="text-xs font-medium text-purple-600 mb-2">{{ __('Requisitos:') }}</p>
                            <ul class="text-sm space-y-1">
                                <li class="flex items-center gap-2">
                                    @if($this->hasMinLength)
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-green-600">{{ __('Mínimo 8 caracteres') }}</span>
                                    @else
                                        <svg class="w-4 h-4 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="text-neutral-500">{{ __('Mínimo 8 caracteres') }}</span>
                                    @endif
                                </li>
                                <li class="flex items-center gap-2">
                                    @if($this->hasNumber)
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-green-600">{{ __('Incluir un número') }}</span>
                                    @else
                                        <svg class="w-4 h-4 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="text-neutral-500">{{ __('Incluir un número') }}</span>
                                    @endif
                                </li>
                                <li class="flex items-center gap-2">
                                    @if($this->hasUppercase)
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-green-600">{{ __('Incluir una mayúscula') }}</span>
                                    @else
                                        <svg class="w-4 h-4 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="text-neutral-500">{{ __('Incluir una mayúscula') }}</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    @endif
                    
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
                    <div class="relative" x-data="{ show: false }">
                        <input wire:model.live="password_confirmation"
                               :type="show ? 'text' : 'password'"
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-2 pr-10 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 {{ $this->passwordConfirmationMatches && !empty($this->password_confirmation) ? 'border-green-500' : ($this->password_confirmation && !$this->passwordConfirmationMatches ? 'border-red-500' : 'border-neutral-300') }}">
                        <div class="absolute right-3 top-2.5 flex items-center gap-2">
                            @if(!empty($this->password_confirmation) && !empty($this->password))
                                <div>
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
                            <button type="button"
                                    @click="show = !show"
                                    class="cursor-pointer text-purple-500 hover:text-purple-700 transition-colors"
                                    title="{{ __('Mostrar/Ocultar contraseña') }}">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
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
