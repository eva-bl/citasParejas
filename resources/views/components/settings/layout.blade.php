<div class="flex items-start max-md:flex-col bg-white min-h-screen">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <nav class="space-y-1">
            <a href="{{ route('profile.edit') }}" 
               wire:navigate
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('profile.edit') ? 'bg-purple-50 text-purple-700' : 'text-purple-600 hover:bg-purple-50 hover:text-purple-700' }}">
                {{ __('Mi Perfil') }}
            </a>
            <a href="{{ route('user-password.edit') }}" 
               wire:navigate
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('user-password.edit') ? 'bg-purple-50 text-purple-700' : 'text-purple-600 hover:bg-purple-50 hover:text-purple-700' }}">
                {{ __('Contraseña') }}
            </a>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ route('two-factor.show') }}" 
                   wire:navigate
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('two-factor.show') ? 'bg-purple-50 text-purple-700' : 'text-purple-600 hover:bg-purple-50 hover:text-purple-700' }}">
                    {{ __('Autenticación de Dos Factores') }}
                </a>
            @endif
            <a href="{{ route('appearance.edit') }}" 
               wire:navigate
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('appearance.edit') ? 'bg-purple-50 text-purple-700' : 'text-purple-600 hover:bg-purple-50 hover:text-purple-700' }}">
                {{ __('Apariencia') }}
            </a>
        </nav>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <h1 class="text-3xl font-bold text-purple-700 mb-2">{{ $heading ?? '' }}</h1>
        <p class="text-base text-purple-600 mb-6">{{ $subheading ?? '' }}</p>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>

