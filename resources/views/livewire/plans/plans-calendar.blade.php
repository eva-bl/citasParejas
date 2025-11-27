<?php

use App\Models\Plan;
use Carbon\Carbon;
use Livewire\Volt\Component;

new class extends Component
{
    public int $currentYear;
    public int $currentMonth;
    public $plans = [];
    public $selectedPlan = null;
    public bool $showPlanModal = false;

    public function mount()
    {
        $this->authorize('viewAny', Plan::class);
        $this->currentYear = now()->year;
        $this->currentMonth = now()->month;
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth();

        $this->plans = Plan::forCouple(auth()->user()->couple_id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with(['category', 'createdBy'])
            ->get()
            ->groupBy(function ($plan) {
                return Carbon::parse($plan->date)->format('Y-m-d');
            })
            ->toArray();
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentYear = $date->year;
        $this->currentMonth = $date->month;
        $this->loadPlans();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentYear = $date->year;
        $this->currentMonth = $date->month;
        $this->loadPlans();
    }

    public function goToToday()
    {
        $this->currentYear = now()->year;
        $this->currentMonth = now()->month;
        $this->loadPlans();
    }

    public function selectPlan($planId)
    {
        $this->selectedPlan = Plan::with(['category', 'createdBy', 'ratings', 'photos'])->find($planId);
        $this->showPlanModal = true;
    }

    public function closeModal()
    {
        $this->showPlanModal = false;
        $this->selectedPlan = null;
    }

    public function getDaysInMonth()
    {
        $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $startOfWeek = $firstDay->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $lastDay->copy()->endOfWeek(Carbon::SUNDAY);
        
        $days = [];
        $current = $startOfWeek->copy();
        
        while ($current <= $endOfWeek) {
            $days[] = [
                'date' => $current->copy(),
                'isCurrentMonth' => $current->month === $this->currentMonth,
                'isToday' => $current->isToday(),
                'plans' => $this->plans[$current->format('Y-m-d')] ?? [],
            ];
            $current->addDay();
        }
        
        return $days;
    }

    public function getMonthName()
    {
        return Carbon::create($this->currentYear, $this->currentMonth, 1)->locale('es')->monthName;
    }
}; ?>

<div>
    <x-layouts.app :title="__('Calendario de Planes')">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">{{ __('Calendario de Planes') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-400 mt-1">{{ __('Visualiza todos tus planes en un calendario mensual') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="goToToday" 
                            class="px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold">
                        {{ __('Hoy') }}
                    </button>
                </div>
            </div>

            <!-- Calendar Navigation -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <button wire:click="previousMonth" 
                            class="p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-all">
                        <svg class="w-6 h-6 text-neutral-700 dark:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <h2 class="text-2xl font-bold text-neutral-900 dark:text-white capitalize">
                        {{ $this->getMonthName() }} {{ $currentYear }}
                    </h2>
                    
                    <button wire:click="nextMonth" 
                            class="p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-all">
                        <svg class="w-6 h-6 text-neutral-700 dark:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-2">
                    <!-- Weekday Headers -->
                    @foreach(['L', 'M', 'X', 'J', 'V', 'S', 'D'] as $day)
                        <div class="text-center font-semibold text-neutral-600 dark:text-neutral-400 py-2">
                            {{ $day }}
                        </div>
                    @endforeach

                    <!-- Calendar Days -->
                    @foreach($this->getDaysInMonth() as $day)
                        <div class="min-h-24 border border-neutral-200 dark:border-neutral-700 rounded-lg p-2 
                            {{ $day['isCurrentMonth'] ? 'bg-white dark:bg-neutral-800' : 'bg-neutral-50 dark:bg-neutral-900/50 opacity-50' }}
                            {{ $day['isToday'] ? 'ring-2 ring-pink-500' : '' }}">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium {{ $day['isToday'] ? 'text-pink-600 dark:text-pink-400' : 'text-neutral-700 dark:text-neutral-300' }}">
                                    {{ $day['date']->day }}
                                </span>
                                @if(count($day['plans']) > 0)
                                    <span class="text-xs bg-pink-500 text-white rounded-full px-2 py-0.5">
                                        {{ count($day['plans']) }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Plans for this day -->
                            <div class="space-y-1">
                                @foreach(array_slice($day['plans'], 0, 2) as $plan)
                                    <button wire:click="selectPlan({{ $plan['id'] }})"
                                            class="w-full text-left px-2 py-1 rounded text-xs font-medium truncate transition-all hover:shadow-md
                                            {{ $plan['status'] === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' }}"
                                            style="border-left: 3px solid {{ $plan['category']['color'] ?? '#ec4899' }}">
                                        {{ $plan['category']['icon'] ?? '📅' }} {{ $plan['title'] }}
                                    </button>
                                @endforeach
                                @if(count($day['plans']) > 2)
                                    <button wire:click="selectPlan({{ $day['plans'][2]['id'] }})"
                                            class="w-full text-left px-2 py-1 rounded text-xs text-neutral-600 dark:text-neutral-400">
                                        +{{ count($day['plans']) - 2 }} más
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Plan Detail Modal -->
        @if($showPlanModal && $selectedPlan)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 wire:click="closeModal"
                 x-data="{ show: @entangle('showPlanModal') }"
                 x-show="show"
                 x-transition>
                <div class="bg-white dark:bg-neutral-800 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
                     @click.stop>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-4xl">{{ $selectedPlan->category->icon ?? '📅' }}</span>
                                    <div>
                                        <h3 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $selectedPlan->title }}</h3>
                                        <p class="text-neutral-600 dark:text-neutral-400">{{ $selectedPlan->date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <button wire:click="closeModal" 
                                    class="p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        @if($selectedPlan->location)
                            <p class="text-neutral-600 dark:text-neutral-400 mb-4">📍 {{ $selectedPlan->location }}</p>
                        @endif

                        @if($selectedPlan->description)
                            <p class="text-neutral-700 dark:text-neutral-300 mb-4 whitespace-pre-line">{{ $selectedPlan->description }}</p>
                        @endif

                        @if($selectedPlan->overall_avg)
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-xl font-bold text-pink-600">{{ number_format($selectedPlan->overall_avg, 1) }}/5</span>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-yellow-400">{{ $i <= round($selectedPlan->overall_avg) ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <a href="{{ route('plans.show', $selectedPlan) }}" 
                               wire:navigate
                               class="px-6 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold">
                                {{ __('Ver Detalles Completos') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-layouts.app>
</div>

