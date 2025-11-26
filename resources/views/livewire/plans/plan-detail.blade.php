<?php

use App\Actions\Plan\DeletePlanAction;
use App\Models\Plan;
use Livewire\Volt\Component;

new class extends Component
{
    public Plan $plan;
    public bool $showDeleteModal = false;

    public function mount(Plan $plan)
    {
        $this->authorize('view', $plan);
        $this->plan = $plan->load(['category', 'createdBy', 'ratings.user', 'photos']);
    }

    public function deletePlan()
    {
        $this->authorize('delete', $this->plan);

        try {
            app(DeletePlanAction::class)->execute(auth()->user(), $this->plan);
            
            session()->flash('success', 'Plan eliminado exitosamente.');
            
            return $this->redirect(route('plans.index'), navigate: true);
        } catch (\Exception $e) {
            $this->addError('plan', $e->getMessage());
            $this->showDeleteModal = false;
        }
    }
}; ?>

<div>
    <x-layouts.app :title="$plan->title">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-4xl">{{ $plan->category->icon ?? '📅' }}</span>
                            <div>
                                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $plan->title }}</h1>
                                <p class="text-neutral-600 dark:text-neutral-400">{{ $plan->date->format('l, d F Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 mt-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $plan->status === 'completed' ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400' }}">
                                {{ $plan->status === 'completed' ? __('Completado') : __('Pendiente') }}
                            </span>
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                {{ __('Creado por') }} {{ $plan->createdBy->name }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <flux:link href="{{ route('plans.edit', $plan) }}" wire:navigate variant="ghost">
                            {{ __('Editar') }}
                        </flux:link>
                        <flux:button wire:click="$set('showDeleteModal', true)" variant="danger" size="sm">
                            {{ __('Eliminar') }}
                        </flux:button>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <!-- Info Card -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-xl font-semibold mb-4 text-neutral-900 dark:text-white">{{ __('Información') }}</h2>
                    <div class="space-y-3">
                        @if($plan->location)
                            <div>
                                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Ubicación:') }}</span>
                                <p class="text-neutral-900 dark:text-white">📍 {{ $plan->location }}</p>
                            </div>
                        @endif
                        @if($plan->cost)
                            <div>
                                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Coste:') }}</span>
                                <p class="text-neutral-900 dark:text-white">💰 {{ number_format($plan->cost, 2) }} €</p>
                            </div>
                        @endif
                        <div>
                            <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Categoría:') }}</span>
                            <p class="text-neutral-900 dark:text-white">{{ $plan->category->icon ?? '' }} {{ $plan->category->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ratings Card -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-xl font-semibold mb-4 text-neutral-900 dark:text-white">{{ __('Valoraciones') }}</h2>
                    @if($plan->overall_avg)
                        <div class="mb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-2xl font-bold text-neutral-900 dark:text-white">{{ number_format($plan->overall_avg, 1) }}</span>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-yellow-400 text-xl">{{ $i <= round($plan->overall_avg) ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Basado en') }} {{ $plan->ratings_count }} {{ __('valoración(es)') }}</p>
                        </div>
                    @else
                        <p class="text-neutral-600 dark:text-neutral-400">{{ __('Aún no hay valoraciones') }}</p>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($plan->description)
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4 text-neutral-900 dark:text-white">{{ __('Descripción') }}</h2>
                    <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-line">{{ $plan->description }}</p>
                </div>
            @endif

            <!-- Photo Upload -->
            @can('create', $plan)
                <div class="mb-6">
                    <livewire:photos.photo-upload :plan="$plan" />
                </div>
            @endcan

            <!-- Photo Gallery -->
            <div class="mb-6">
                <livewire:photos.photo-gallery :plan="$plan" />
            </div>

            <!-- Rating Form -->
            @can('create', $plan)
                <div class="mb-6">
                    <livewire:ratings.rating-form :plan="$plan" />
                </div>
            @endcan

            <!-- Ratings Display -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold mb-4 text-neutral-900 dark:text-white">{{ __('Valoraciones Detalladas') }}</h2>
                @if($plan->ratings->count() > 0)
                    <div class="space-y-6">
                        @foreach($plan->ratings as $rating)
                            <div class="border-b border-neutral-200 dark:border-neutral-700 pb-6 last:border-0 last:pb-0">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <span class="font-semibold text-neutral-900 dark:text-white">{{ $rating->user->name }}</span>
                                        @if($rating->user_id === auth()->id())
                                            <span class="ml-2 text-xs text-pink-600 dark:text-pink-400">({{ __('Tu valoración') }})</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl font-bold text-pink-600">{{ $rating->overall }}</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-yellow-400 text-lg">{{ $i <= $rating->overall ? '★' : '☆' }}</span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Detailed Ratings -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3 text-sm">
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">{{ __('Diversión') }}:</span>
                                        <span class="font-medium text-neutral-900 dark:text-white ml-1">{{ $rating->fun }}/5</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">{{ __('Conexión') }}:</span>
                                        <span class="font-medium text-neutral-900 dark:text-white ml-1">{{ $rating->emotional_connection }}/5</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">{{ __('Organización') }}:</span>
                                        <span class="font-medium text-neutral-900 dark:text-white ml-1">{{ $rating->organization }}/5</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">{{ __('Calidad-Precio') }}:</span>
                                        <span class="font-medium text-neutral-900 dark:text-white ml-1">{{ $rating->value_for_money }}/5</span>
                                    </div>
                                </div>

                                @if($rating->comment)
                                    <div class="mt-3 p-3 bg-neutral-50 dark:bg-neutral-700/50 rounded-lg">
                                        <p class="text-sm text-neutral-700 dark:text-neutral-300 whitespace-pre-line">{{ $rating->comment }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-600 dark:text-neutral-400">{{ __('Aún no hay valoraciones') }}</p>
                @endif
            </div>

            <!-- Delete Modal -->
            <flux:modal name="delete-plan" :show="$showDeleteModal">
                <form wire:submit="deletePlan" class="space-y-4">
                    <flux:heading>{{ __('Eliminar Plan') }}</flux:heading>
                    <flux:subheading>{{ __('¿Estás seguro de que quieres eliminar este plan? Esta acción no se puede deshacer.') }}</flux:subheading>
                    
                    @error('plan')
                        <div class="p-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 rounded">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="flex items-center justify-end gap-4">
                        <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">
                            {{ __('Cancelar') }}
                        </flux:button>
                        <flux:button type="submit" variant="danger">
                            {{ __('Eliminar') }}
                        </flux:button>
                    </div>
                </form>
            </flux:modal>
        </div>
    </x-layouts.app>
</div>

