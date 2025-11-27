<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public ?string $nickname = null;
    public string $timezone = 'UTC';
    public string $locale = 'es';
    public $avatar = null;
    public $showAvatarMenu = false;

    /**
     * Get timezones for the select
     */
    public function getTimezonesProperty(): array
    {
        $timezones = [];
        $identifiers = timezone_identifiers_list();
        
        foreach ($identifiers as $timezone) {
            try {
                $offset = (new \DateTime('now', new \DateTimeZone($timezone)))->getOffset();
                $hours = intval($offset / 3600);
                $minutes = abs(($offset % 3600) / 60);
                $offsetString = sprintf('%+03d:%02d', $hours, $minutes);
                $timezones[$timezone] = "{$timezone} (UTC{$offsetString})";
            } catch (\Exception $e) {
                // Skip invalid timezones
                continue;
            }
        }
        
        return $timezones;
    }

    /**
     * Get locales for the select
     */
    public function getLocalesProperty(): array
    {
        return [
            'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
            'en' => ['name' => 'English', 'flag' => '🇬🇧'],
            'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
            'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
            'it' => ['name' => 'Italiano', 'flag' => '🇮🇹'],
            'pt' => ['name' => 'Português', 'flag' => '🇵🇹'],
            'ru' => ['name' => 'Русский', 'flag' => '🇷🇺'],
            'zh' => ['name' => '中文', 'flag' => '🇨🇳'],
            'ja' => ['name' => '日本語', 'flag' => '🇯🇵'],
            'ko' => ['name' => '한국어', 'flag' => '🇰🇷'],
            'ar' => ['name' => 'العربية', 'flag' => '🇸🇦'],
            'nl' => ['name' => 'Nederlands', 'flag' => '🇳🇱'],
            'pl' => ['name' => 'Polski', 'flag' => '🇵🇱'],
            'sv' => ['name' => 'Svenska', 'flag' => '🇸🇪'],
            'da' => ['name' => 'Dansk', 'flag' => '🇩🇰'],
            'no' => ['name' => 'Norsk', 'flag' => '🇳🇴'],
            'fi' => ['name' => 'Suomi', 'flag' => '🇫🇮'],
            'cs' => ['name' => 'Čeština', 'flag' => '🇨🇿'],
            'tr' => ['name' => 'Türkçe', 'flag' => '🇹🇷'],
            'el' => ['name' => 'Ελληνικά', 'flag' => '🇬🇷'],
            'he' => ['name' => 'עברית', 'flag' => '🇮🇱'],
            'hi' => ['name' => 'हिन्दी', 'flag' => '🇮🇳'],
            'th' => ['name' => 'ไทย', 'flag' => '🇹🇭'],
            'vi' => ['name' => 'Tiếng Việt', 'flag' => '🇻🇳'],
            'id' => ['name' => 'Bahasa Indonesia', 'flag' => '🇮🇩'],
            'ms' => ['name' => 'Bahasa Melayu', 'flag' => '🇲🇾'],
        ];
    }

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nickname = $user->nickname;
        $this->timezone = $user->timezone ?? 'UTC';
        $this->locale = $user->locale ?? 'es';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'timezone' => ['required', 'string', 'max:255'],
            'locale' => ['required', 'string', 'max:10'],
            // Email is not editable, so we don't validate it
        ]);

        // Update only allowed fields (exclude email)
        $user->name = $validated['name'];
        $user->nickname = $validated['nickname'] ?? null;
        $user->timezone = $validated['timezone'];
        $user->locale = $validated['locale'];
        $user->save();

        // Update session locale and timezone
        Session::put('locale', $this->locale);
        app()->setLocale($this->locale);
        date_default_timezone_set($this->timezone);

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Upload avatar from file
     */
    public function uploadAvatar(): void
    {
        $this->validate([
            'avatar' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Store new avatar
        $path = $this->avatar->store('avatars', 'public');
        $user->avatar_path = $path;
        $user->save();

        // Refresh user in session
        Auth::setUser($user->fresh());

        $this->avatar = null;
        $this->showAvatarMenu = false;
        
        // Refresh the component to show the new avatar
        $this->dispatch('avatar-updated');
    }

    /**
     * Remove avatar
     */
    public function removeAvatar(): void
    {
        $user = Auth::user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $user->avatar_path = null;
            $user->save();
        }

        // Refresh user in session
        Auth::setUser($user->fresh());

        $this->showAvatarMenu = false;
        $this->dispatch('avatar-updated');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

}; ?>

<section class="w-full min-h-screen py-8">
    <x-settings.layout>
        <!-- Card Container -->
        <div class="max-w-3xl mx-auto bg-white/60 backdrop-blur-xl shadow-lg rounded-2xl p-8 space-y-6">
            <!-- Avatar and Name Section -->
            <div class="flex items-center gap-6">
                <div class="relative group flex-shrink-0">
                    @php
                        $user = auth()->user()->fresh();
                    @endphp
                    @if($user->hasAvatar())
                        <img src="{{ $user->avatar_url }}" 
                             alt="{{ $user->name }}" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                            {{ $user->initials() }}
                        </div>
                    @endif
                    
                    <!-- Edit Button -->
                    <div x-data="{ open: false }" class="absolute bottom-0 right-0">
                        <button @click="open = !open" 
                                class="w-10 h-10 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 text-white flex items-center justify-center shadow-lg hover:shadow-xl transition-all transform hover:scale-110"
                                title="{{ __('Editar foto de perfil') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="py-1" role="menu">
                                <!-- Upload Photo -->
                                <label class="flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-pink-50 hover:text-pink-600 transition-colors cursor-pointer" role="menuitem">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ __('Elegir desde galería') }}</span>
                                    <input type="file" 
                                           wire:model="avatar" 
                                           accept="image/*" 
                                           class="hidden"
                                           @change="$wire.uploadAvatar()">
                                </label>

                                <!-- Upload Photo File -->
                                <label class="flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-pink-50 hover:text-pink-600 transition-colors cursor-pointer" role="menuitem">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span>{{ __('Subir foto') }}</span>
                                    <input type="file" 
                                           wire:model="avatar" 
                                           accept="image/*" 
                                           class="hidden"
                                           @change="$wire.uploadAvatar()">
                                </label>

                                <!-- Choose Avatar -->
                                <button @click="open = false; $wire.showAvatarMenu = true" 
                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-pink-50 hover:text-pink-600 transition-colors text-left" 
                                        role="menuitem">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ __('Elegir avatar') }}</span>
                                </button>

                                @if(auth()->user()->hasAvatar())
                                    <div class="border-t border-neutral-200 my-1"></div>
                                    <button @click="open = false; $wire.removeAvatar()" 
                                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left" 
                                            role="menuitem">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>{{ __('Quitar foto de perfil') }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-purple-700">{{ $user->name }}</h1>
                </div>
            </div>

            <form wire:submit="updateProfileInformation" class="w-full space-y-6">
            <div>
                <label class="block text-sm font-medium text-purple-600 mb-2">
                    {{ __('Nombre') }}
                </label>
                <input wire:model="name" 
                       type="text" 
                       required 
                       autofocus 
                       autocomplete="name"
                       class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700">
            </div>

            <div>
                <label class="block text-sm font-medium text-purple-600 mb-2">
                    {{ __('Correo electrónico') }}
                </label>
                <input wire:model="email" 
                       type="email" 
                       readonly
                       disabled
                       autocomplete="email"
                       class="w-full px-4 py-2 border border-neutral-300 rounded-lg bg-neutral-100 text-purple-700 cursor-not-allowed">

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div class="mt-4">
                        <p class="text-sm text-purple-600">
                            {{ __('Tu dirección de correo electrónico no está verificada.') }}
                            <button type="button" 
                                    wire:click.prevent="resendVerificationNotification"
                                    class="text-sm font-semibold text-purple-700 hover:text-purple-800 hover:underline">
                                {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-green-600">
                                {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-purple-600 mb-2">
                    {{ __('Apodo') }}
                </label>
                <input wire:model="nickname" 
                       type="text" 
                       autocomplete="nickname"
                       placeholder="{{ __('Opcional') }}"
                       class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700">
                <p class="mt-1 text-xs text-neutral-500">{{ __('Un apodo o alias que te identifique') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-purple-600 mb-2">
                    {{ __('Zona horaria') }}
                </label>
                <select wire:model="timezone" 
                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 bg-white">
                    @foreach($this->timezones as $tz => $label)
                        <option value="{{ $tz }}">{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-neutral-500">{{ __('La aplicación se adaptará a esta zona horaria') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-purple-600 mb-2">
                    {{ __('Idioma') }}
                </label>
                <select wire:model="locale" 
                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 bg-white">
                    @foreach($this->locales as $loc => $data)
                        <option value="{{ $loc }}">{{ $data['flag'] }} {{ $data['name'] }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-neutral-500">{{ __('La aplicación se traducirá a este idioma') }}</p>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                        data-test="update-profile-button">
                    {{ __('Guardar') }}
                </button>

                <x-action-message class="me-3 text-purple-600 font-medium" on="profile-updated">
                    {{ __('Guardado.') }}
                </x-action-message>
                </div>
            </form>

            <livewire:settings.delete-user-form />
        </div>
    </x-settings.layout>
</section>
