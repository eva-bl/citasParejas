<?php

use App\Models\Plan;
use Livewire\Volt\Component;

new class extends Component
{
    public function plans()
    {
        return auth()->user()
            ->favoritePlans()
            ->with(['category', 'createdBy'])
            ->withCount(['ratings', 'photos'])
            ->orderBy('date', 'desc')
            ->paginate(12);
    }
}; ?>

<div>
    <x-layouts.app :title="__('Planes Favoritos')">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-2">{{ __('Planes Favoritos') }}</h1>
                <p class="text-neutral-600 dark:text-neutral-400">{{ __('Tus planes marcados como favoritos') }}</p>
            </div>

            @php
                $plans = $this->plans();
            @endphp

            @if($plans->count() > 0)
                <!-- Plans Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($plans as $plan)
                        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <!-- Plan Image/Icon -->
                            <div class="h-48 bg-gradient-to-br from-pink-400 via-purple-500 to-blue-500 flex items-center justify-center relative">
                                @if($plan->category)
                                    <div class="text-6xl">{{ $plan->category->icon }}</div>
                                @else
                                    <div class="text-6xl">📅</div>
                                @endif
                                
                                <!-- Favorite Badge -->
                                <div class="absolute top-4 right-4">
                                    <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Plan Content -->
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-1">{{ $plan->title }}</h3>
                                        <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ \Carbon\Carbon::parse($plan->date)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    @if($plan->category)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $plan->category->color ?? '#ec4899' }}20; color: {{ $plan->category->color ?? '#ec4899' }}">
                                            {{ $plan->category->icon }} {{ $plan->category->name }}
                                        </span>
                                    @endif
                                </div>

                                @if($plan->location)
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $plan->location }}
                                    </p>
                                @endif

                                <!-- Stats -->
                                <div class="flex items-center gap-4 mb-4 text-sm text-neutral-600 dark:text-neutral-400">
                                    @if($plan->overall_avg)
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="font-semibold">{{ number_format($plan->overall_avg, 1) }}/5</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $plan->photos_count }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('plans.show', $plan) }}" 
                                       wire:navigate
                                       class="flex-1 px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white text-center rounded-lg hover:shadow-lg transition-all font-semibold">
                                        {{ __('Ver Detalles') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $plans->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="text-6xl mb-4">⭐</div>
                    <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-2">{{ __('No tienes planes favoritos aún') }}</h3>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-6">{{ __('Marca tus planes favoritos para encontrarlos fácilmente') }}</p>
                    <a href="{{ route('plans.index') }}" 
                       wire:navigate
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl hover:shadow-lg transition-all font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Ver Mis Planes') }}
                    </a>
                </div>
            @endif
        </div>
    </x-layouts.app>
</div>




