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
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @foreach($allBadges as $badge)
            @php
                $hasBadge = $this->hasBadge($badge['id']);
                $userBadge = collect($userBadges)->firstWhere('id', $badge['id']);
            @endphp
            
            <div class="relative group">
                <div class="relative overflow-hidden rounded-2xl p-6 transition-all duration-300 
                    {{ $hasBadge 
                        ? 'bg-gradient-to-br from-yellow-400 via-yellow-300 to-orange-400 shadow-xl transform hover:scale-105' 
                        : 'bg-neutral-100 border-2 border-neutral-300 opacity-60' 
                    }}">
                    <!-- Icono -->
                    <div class="text-center mb-4">
                        <div class="text-6xl mb-2 {{ $hasBadge ? '' : 'grayscale opacity-50' }}">
                            {{ $badge['icon'] }}
                        </div>
                    </div>
                    
                    <!-- Nombre -->
                    <h3 class="text-center font-bold text-sm mb-2 {{ $hasBadge ? 'text-neutral-900' : 'text-neutral-500' }}">
                        {{ $badge['name'] }}
                    </h3>
                    
                    <!-- Descripción -->
                    <p class="text-xs text-center {{ $hasBadge ? 'text-neutral-700' : 'text-neutral-400' }}">
                        {{ $badge['description'] }}
                    </p>
                    
                    <!-- Fecha de obtención -->
                    @if($hasBadge && $userBadge)
                        <div class="mt-3 text-center">
                            <p class="text-xs text-neutral-600">
                                {{ __('Obtenida') }}: {{ \Carbon\Carbon::parse($userBadge['pivot']['obtained_at'])->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif
                    
                    <!-- Efecto de brillo para insignias obtenidas -->
                    @if($hasBadge)
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-200/30 to-orange-200/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    @endif
                </div>
                
                <!-- Indicador de bloqueada -->
                @if(!$hasBadge)
                    <div class="absolute top-2 right-2">
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    
    <!-- Resumen -->
    <div class="mt-8 p-6 bg-gradient-to-r from-pink-50 to-purple-50 rounded-2xl border border-pink-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-neutral-900 mb-1">{{ __('Progreso de Insignias') }}</h3>
                <p class="text-sm text-neutral-600">
                    {{ count($userBadges) }} {{ __('de') }} {{ count($allBadges) }} {{ __('insignias obtenidas') }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-pink-600">
                    {{ round((count($userBadges) / max(count($allBadges), 1)) * 100) }}%
                </div>
                <p class="text-xs text-neutral-600">{{ __('Completado') }}</p>
            </div>
        </div>
        
        <!-- Barra de progreso -->
        <div class="mt-4 w-full bg-neutral-200 rounded-full h-3">
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                 style="width: {{ (count($userBadges) / max(count($allBadges), 1)) * 100 }}%"></div>
        </div>
    </div>
</div>
