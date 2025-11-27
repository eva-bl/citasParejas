<?php

use App\Services\BadgeEvaluationService;
use Livewire\Volt\Component;

new class extends Component
{
    public $userBadges = [];
    public $allBadges = [];
    
    public function mount(BadgeEvaluationService $badgeService)
    {
        $this->userBadges = $badgeService->getUserBadges(auth()->user())->toArray();
        $this->allBadges = $badgeService->getAllBadges()->toArray();
    }
    
    public function getUserBadgeIds()
    {
        return collect($this->userBadges)->pluck('id')->toArray();
    }
    
    public function hasBadge($badgeId)
    {
        return in_array($badgeId, $this->getUserBadgeIds());
    }
}; ?>

<div class="space-y-6">
    <x-layouts.app :title="__('Mis Insignias')">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ __('Mis Insignias') }}</h1>
            <p class="text-neutral-600">{{ __('Insignias que has obtenido por tus logros en pareja') }}</p>
        </div>
        
        <livewire:badges.user-badges-display />
    </x-layouts.app>
</div>
