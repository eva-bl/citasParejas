<?php

use App\Services\BadgeEvaluationService;
use Livewire\Volt\Component;

new class extends Component
{
    public $user1Badges = [];
    public $user2Badges = [];
    public $allBadges = [];
    
    public function mount(BadgeEvaluationService $badgeService)
    {
        if (!auth()->user()->hasCouple()) {
            return;
        }
        
        $user1 = auth()->user();
        $user2 = auth()->user()->partner();
        
        $this->user1Badges = $badgeService->getUserBadges($user1)->pluck('id')->toArray();
        $this->allBadges = $badgeService->getAllBadges()->toArray();
        
        if ($user2) {
            $this->user2Badges = $badgeService->getUserBadges($user2)->pluck('id')->toArray();
        }
    }
    
    public function hasBadge($badgeId, $user = 'both')
    {
        if ($user === 'user1') {
            return in_array($badgeId, $this->user1Badges);
        } elseif ($user === 'user2') {
            return in_array($badgeId, $this->user2Badges);
        }
        
        // Both users
        return in_array($badgeId, $this->user1Badges) || in_array($badgeId, $this->user2Badges);
    }
}; ?>

<div>
    @if(auth()->user()->hasCouple())
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-neutral-900 mb-2">{{ __('Insignias de la Pareja') }}</h2>
            <p class="text-neutral-600">{{ __('Insignias obtenidas por ambos miembros de la pareja') }}</p>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($allBadges as $badge)
                @php
                    $user1Has = $this->hasBadge($badge['id'], 'user1');
                    $user2Has = $this->hasBadge($badge['id'], 'user2');
                    $bothHave = $user1Has && $user2Has;
                @endphp
                
                <div class="relative group">
                    <div class="relative overflow-hidden rounded-2xl p-6 transition-all duration-300 
                        {{ $bothHave 
                            ? 'bg-gradient-to-br from-pink-500 via-purple-500 to-blue-500 shadow-xl transform hover:scale-105' 
                            : ($user1Has || $user2Has
                                ? 'bg-gradient-to-br from-yellow-400 via-yellow-300 to-orange-400 shadow-lg'
                                : 'bg-neutral-100 border-2 border-neutral-300 opacity-60')
                        }}">
                        <!-- Icono -->
                        <div class="text-center mb-4">
                            <div class="text-6xl mb-2 {{ ($user1Has || $user2Has) ? '' : 'grayscale opacity-50' }}">
                                {{ $badge['icon'] }}
                            </div>
                        </div>
                        
                        <!-- Nombre -->
                        <h3 class="text-center font-bold text-sm mb-2 {{ ($user1Has || $user2Has) ? 'text-neutral-900' : 'text-neutral-500' }}">
                            {{ $badge['name'] }}
                        </h3>
                        
                        <!-- Estado -->
                        <div class="text-center">
                            @if($bothHave)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-white/30 rounded-full text-xs font-semibold text-white">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Ambos') }}
                                </span>
                            @elseif($user1Has)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-500/30 rounded-full text-xs font-semibold text-yellow-900">
                                    {{ auth()->user()->name }}
                                </span>
                            @elseif($user2Has)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-500/30 rounded-full text-xs font-semibold text-yellow-900">
                                    {{ auth()->user()->partner()->name }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-neutral-400/30 rounded-full text-xs font-semibold text-neutral-600">
                                    {{ __('Bloqueada') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Resumen -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl text-white">
                <h3 class="text-lg font-bold mb-2">{{ auth()->user()->name }}</h3>
                <p class="text-3xl font-bold">{{ count($user1Badges) }}</p>
                <p class="text-sm text-pink-100">{{ __('Insignias obtenidas') }}</p>
            </div>
            
            @if(auth()->user()->partner())
                <div class="p-6 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl text-white">
                    <h3 class="text-lg font-bold mb-2">{{ auth()->user()->partner()->name }}</h3>
                    <p class="text-3xl font-bold">{{ count($user2Badges) }}</p>
                    <p class="text-sm text-purple-100">{{ __('Insignias obtenidas') }}</p>
                </div>
            @endif
            
            <div class="p-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl text-white">
                <h3 class="text-lg font-bold mb-2">{{ __('En Común') }}</h3>
                <p class="text-3xl font-bold">{{ count(array_intersect($user1Badges, $user2Badges)) }}</p>
                <p class="text-sm text-blue-100">{{ __('Insignias compartidas') }}</p>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-neutral-600 text-lg">{{ __('Necesitas estar en una pareja para ver las insignias.') }}</p>
        </div>
    @endif
</div>



