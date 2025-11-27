<?php

use Livewire\Volt\Component;

new class extends Component
{
    //
}; ?>

<div>
    <x-layouts.app :title="__('Mis Insignias')">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ __('Mis Insignias') }}</h1>
            <p class="text-neutral-600">{{ __('Insignias que has obtenido por tus logros en pareja') }}</p>
        </div>
        
        <livewire:badges.user-badges-display />
    </x-layouts.app>
</div>

