<?php

use App\Actions\Statistics\GetCoupleStatisticsAction;
use Livewire\Volt\Component;

new class extends Component
{
    public array $stats = [];
    
    public function mount(GetCoupleStatisticsAction $getStats)
    {
        if (!auth()->user()->hasCouple()) {
            return;
        }
        
        $this->stats = $getStats->execute(auth()->user()->couple);
    }
    
    public function refreshStats(GetCoupleStatisticsAction $getStats)
    {
        if (!auth()->user()->hasCouple()) {
            return;
        }
        
        $this->stats = $getStats->execute(auth()->user()->couple);
    }
}; ?>

<div>
    <x-layouts.app :title="__('Estadísticas de Pareja')">
        @if(auth()->user()->hasCouple())
            <div class="space-y-6">
                <!-- KPIs Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Planes -->
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-pink-500 via-pink-400 to-purple-500 p-6 shadow-xl">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-white/80 text-sm font-medium mb-1">{{ __('Total Planes') }}</h3>
                            <p class="text-3xl font-bold text-white">{{ $stats['total_plans'] ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Nota Media -->
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 via-purple-400 to-blue-500 p-6 shadow-xl">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-white/80 text-sm font-medium mb-1">{{ __('Nota Media') }}</h3>
                            <p class="text-3xl font-bold text-white">{{ number_format($stats['overall_avg'] ?? 0, 1) }}/5</p>
                        </div>
                    </div>

                    <!-- Planes Completados -->
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 via-blue-400 to-cyan-500 p-6 shadow-xl">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-white/80 text-sm font-medium mb-1">{{ __('Completados') }}</h3>
                            <p class="text-3xl font-bold text-white">{{ $stats['completed_plans'] ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Total Fotos -->
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500 via-teal-400 to-green-500 p-6 shadow-xl">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-white/80 text-sm font-medium mb-1">{{ __('Total Fotos') }}</h3>
                            <p class="text-3xl font-bold text-white">{{ $stats['total_photos'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Detalladas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Valoraciones por Criterio -->
                    <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-lg">
                        <h3 class="text-xl font-bold text-neutral-900 mb-6">{{ __('Valoraciones por Criterio') }}</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-neutral-700">{{ __('Diversión') }}</span>
                                    <span class="text-sm font-bold text-pink-600">{{ number_format($stats['fun_avg'] ?? 0, 1) }}/5</span>
                                </div>
                                <div class="w-full bg-neutral-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-pink-500 to-pink-600 h-3 rounded-full" 
                                         style="width: {{ (($stats['fun_avg'] ?? 0) / 5) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-neutral-700">{{ __('Conexión Emocional') }}</span>
                                    <span class="text-sm font-bold text-purple-600">{{ number_format($stats['emotional_connection_avg'] ?? 0, 1) }}/5</span>
                                </div>
                                <div class="w-full bg-neutral-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full" 
                                         style="width: {{ (($stats['emotional_connection_avg'] ?? 0) / 5) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-neutral-700">{{ __('Organización') }}</span>
                                    <span class="text-sm font-bold text-blue-600">{{ number_format($stats['organization_avg'] ?? 0, 1) }}/5</span>
                                </div>
                                <div class="w-full bg-neutral-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full" 
                                         style="width: {{ (($stats['organization_avg'] ?? 0) / 5) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-neutral-700">{{ __('Relación Calidad-Precio') }}</span>
                                    <span class="text-sm font-bold text-cyan-600">{{ number_format($stats['value_for_money_avg'] ?? 0, 1) }}/5</span>
                                </div>
                                <div class="w-full bg-neutral-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-3 rounded-full" 
                                         style="width: {{ (($stats['value_for_money_avg'] ?? 0) / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categorías -->
                    <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-lg">
                        <h3 class="text-xl font-bold text-neutral-900 mb-6">{{ __('Categorías') }}</h3>
                        @if(isset($stats['category_stats']['best']) || isset($stats['category_stats']['worst']))
                            <div class="space-y-4">
                                @if(isset($stats['category_stats']['best']))
                                    <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="text-2xl">{{ $stats['category_stats']['best']['icon'] ?? '🏆' }}</span>
                                            <div>
                                                <p class="font-semibold text-green-900">{{ __('Mejor Valorada') }}</p>
                                                <p class="text-sm text-green-700">{{ $stats['category_stats']['best']['name'] }}</p>
                                            </div>
                                        </div>
                                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['category_stats']['best']['avg_rating'], 1) }}/5</p>
                                    </div>
                                @endif
                                
                                @if(isset($stats['category_stats']['worst']))
                                    <div class="p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border border-orange-200">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="text-2xl">{{ $stats['category_stats']['worst']['icon'] ?? '📊' }}</span>
                                            <div>
                                                <p class="font-semibold text-orange-900">{{ __('Menor Valorada') }}</p>
                                                <p class="text-sm text-orange-700">{{ $stats['category_stats']['worst']['name'] }}</p>
                                            </div>
                                        </div>
                                        <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['category_stats']['worst']['avg_rating'], 1) }}/5</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-neutral-500 text-center py-8">{{ __('Aún no hay suficientes datos para mostrar estadísticas de categorías.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Botón de Actualizar -->
                <div class="flex justify-end">
                    <button wire:click="refreshStats" 
                            class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold rounded-xl hover:shadow-xl transition-all transform hover:scale-105">
                        {{ __('Actualizar Estadísticas') }}
                    </button>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-neutral-600 text-lg">{{ __('Necesitas estar en una pareja para ver las estadísticas.') }}</p>
            </div>
        @endif
    </x-layouts.app>
</div>



