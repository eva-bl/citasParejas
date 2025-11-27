<div class="flex items-start max-md:flex-col bg-white min-h-screen pt-4">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <nav class="space-y-2">
            <!-- Mi Perfil -->
            <a href="{{ route('profile.edit') }}" 
               wire:navigate
               class="rounded-xl px-4 py-2 flex items-center gap-2 transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'bg-purple-600 text-white font-semibold shadow-sm ml-2' : 'text-purple-700 hover:bg-purple-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-sm">{{ __('Mi Perfil') }}</span>
            </a>

            <!-- Contraseña -->
            <a href="{{ route('user-password.edit') }}" 
               wire:navigate
               class="rounded-xl px-4 py-2 flex items-center gap-2 transition-all duration-200 {{ request()->routeIs('user-password.edit') ? 'bg-purple-600 text-white font-semibold shadow-sm ml-2' : 'text-purple-700 hover:bg-purple-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span class="text-sm">{{ __('Contraseña') }}</span>
            </a>

            <!-- Autenticación de Dos Factores -->
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ route('two-factor.show') }}" 
                   wire:navigate
                   class="rounded-xl px-4 py-2 flex items-center gap-2 transition-all duration-200 {{ request()->routeIs('two-factor.show') ? 'bg-purple-600 text-white font-semibold shadow-sm ml-2' : 'text-purple-700 hover:bg-purple-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <span class="text-sm">{{ __('Autenticación de Dos Factores') }}</span>
                </a>
            @endif

            <!-- Apariencia -->
            <a href="{{ route('appearance.edit') }}" 
               wire:navigate
               class="rounded-xl px-4 py-2 flex items-center gap-2 transition-all duration-200 {{ request()->routeIs('appearance.edit') ? 'bg-purple-600 text-white font-semibold shadow-sm ml-2' : 'text-purple-700 hover:bg-purple-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
                <span class="text-sm">{{ __('Apariencia') }}</span>
            </a>
        </nav>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        @if(isset($heading) && $heading)
            <h1 class="text-3xl font-bold text-purple-700 mb-2">{{ $heading }}</h1>
        @endif
        @if(isset($subheading) && $subheading)
            <p class="text-base text-purple-600 mb-6">{{ $subheading }}</p>
        @endif

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>

