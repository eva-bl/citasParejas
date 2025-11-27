<?php

use App\Models\Category;
use App\Models\Plan;
use Livewire\Volt\Component;

new class extends Component
{

    public ?int $categoryFilter = null;
    public ?string $statusFilter = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?int $createdByFilter = null;
    public ?float $ratingMin = null;
    public ?float $ratingMax = null;
    public ?float $costMin = null;
    public ?float $costMax = null;
    public bool $onlyFavorites = false;
    public bool $withoutRatings = false;
    public bool $withoutPhotos = false;
    public string $search = '';
    public string $sortBy = 'date';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'createdByFilter' => ['except' => ''],
        'ratingMin' => ['except' => ''],
        'ratingMax' => ['except' => ''],
        'costMin' => ['except' => ''],
        'costMax' => ['except' => ''],
        'onlyFavorites' => ['except' => false],
        'withoutRatings' => ['except' => false],
        'withoutPhotos' => ['except' => false],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->authorize('viewAny', Plan::class);
    }

    public function updatingSearch()
    {
        //
    }

    public function clearFilters()
    {
        $this->categoryFilter = null;
        $this->statusFilter = null;
        $this->dateFrom = null;
        $this->dateTo = null;
        $this->createdByFilter = null;
        $this->ratingMin = null;
        $this->ratingMax = null;
        $this->costMin = null;
        $this->costMax = null;
        $this->onlyFavorites = false;
        $this->withoutRatings = false;
        $this->withoutPhotos = false;
        $this->search = '';
    }

    public function plans()
    {
        $query = Plan::query()
            ->forCouple(auth()->user()->couple_id)
            ->with(['category', 'createdBy'])
            ->withCount(['ratings', 'photos']);

        // Search - Full text search including comments
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%')
                  ->orWhereHas('ratings', function ($ratingQuery) {
                      $ratingQuery->where('comment', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filters
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFrom) {
            $query->whereDate('date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('date', '<=', $this->dateTo);
        }

        if ($this->createdByFilter) {
            $query->where('created_by', $this->createdByFilter);
        }

        // Rating filters
        if ($this->ratingMin !== null) {
            $query->where('overall_avg', '>=', $this->ratingMin);
        }
        if ($this->ratingMax !== null) {
            $query->where('overall_avg', '<=', $this->ratingMax);
        }

        // Cost filters
        if ($this->costMin !== null) {
            $query->where('cost', '>=', $this->costMin);
        }
        if ($this->costMax !== null) {
            $query->where('cost', '<=', $this->costMax);
        }

        // Only favorites
        if ($this->onlyFavorites) {
            $query->whereHas('favoritedBy', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        // Without ratings
        if ($this->withoutRatings) {
            $query->whereNull('overall_avg');
        }

        // Without photos
        if ($this->withoutPhotos) {
            $query->where('photos_count', 0);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(12);
    }
}; ?>

<x-layouts.app :title="__('Mis Planes')">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">{{ __('Mis Planes') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-400 mt-1">{{ __('Gestiona todos tus planes y citas') }}</p>
                </div>
                <flux:link href="{{ route('plans.create') }}" wire:navigate variant="primary">
                    {{ __('+ Nuevo Plan') }}
                </flux:link>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <flux:field>
                            <flux:label>{{ __('Buscar') }}</flux:label>
                            <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Buscar por título, descripción o ubicación...') }}" />
                        </flux:field>
                    </div>

                    <!-- Category Filter -->
                    <flux:field>
                        <flux:label>{{ __('Categoría') }}</flux:label>
                        <flux:select wire:model.live="categoryFilter">
                            <option value="">{{ __('Todas') }}</option>
                            @foreach(Category::orderBy('name')->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->icon }} {{ $category->name }}</option>
                            @endforeach
                        </flux:select>
                    </flux:field>

                    <!-- Status Filter -->
                    <flux:field>
                        <flux:label>{{ __('Estado') }}</flux:label>
                        <flux:select wire:model.live="statusFilter">
                            <option value="">{{ __('Todos') }}</option>
                            <option value="pending">{{ __('Pendiente') }}</option>
                            <option value="completed">{{ __('Completado') }}</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mt-4">
                    <!-- Date From -->
                    <flux:field>
                        <flux:label>{{ __('Fecha Desde') }}</flux:label>
                        <flux:input type="date" wire:model.live="dateFrom" />
                    </flux:field>

                    <!-- Date To -->
                    <flux:field>
                        <flux:label>{{ __('Fecha Hasta') }}</flux:label>
                        <flux:input type="date" wire:model.live="dateTo" />
                    </flux:field>

                    <!-- Created By -->
                    <flux:field>
                        <flux:label>{{ __('Creado Por') }}</flux:label>
                        <flux:select wire:model.live="createdByFilter">
                            <option value="">{{ __('Ambos') }}</option>
                            <option value="{{ auth()->id() }}">{{ __('Yo') }}</option>
                            @if(auth()->user()->partner())
                                <option value="{{ auth()->user()->partner()->id }}">{{ auth()->user()->partner()->name }}</option>
                            @endif
                        </flux:select>
                    </flux:field>
                </div>

                @if($categoryFilter || $statusFilter || $dateFrom || $dateTo || $createdByFilter || $search)
                    <div class="mt-4">
                        <flux:button wire:click="clearFilters" variant="ghost" size="sm">
                            {{ __('Limpiar Filtros') }}
                        </flux:button>
                    </div>
                @endif
            </div>

            <!-- Sort -->
            @php
                $plans = $this->plans();
            @endphp
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm text-neutral-600 dark:text-neutral-400">
                    {{ __('Mostrando') }} {{ $plans->firstItem() ?? 0 }} - {{ $plans->lastItem() ?? 0 }} {{ __('de') }} {{ $plans->total() }} {{ __('planes') }}
                </div>
                <div class="flex items-center gap-2">
                    <flux:label class="text-sm">{{ __('Ordenar por:') }}</flux:label>
                    <flux:select wire:model.live="sortBy" size="sm">
                        <option value="date">{{ __('Fecha') }}</option>
                        <option value="overall_avg">{{ __('Valoración') }}</option>
                        <option value="cost">{{ __('Coste') }}</option>
                        <option value="title">{{ __('Título') }}</option>
                    </flux:select>
                    <flux:button wire:click="$set('sortDirection', $sortDirection === 'asc' ? 'desc' : 'asc')" variant="ghost" size="sm">
                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                    </flux:button>
                </div>
            </div>

            <!-- Plans Grid -->
            @if($plans->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($plans as $plan)
                        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">{{ $plan->category->icon ?? '📅' }}</span>
                                    <div>
                                        <h3 class="font-semibold text-neutral-900 dark:text-white">{{ $plan->title }}</h3>
                                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $plan->date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full {{ $plan->status === 'completed' ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400' }}">
                                    {{ $plan->status === 'completed' ? __('Completado') : __('Pendiente') }}
                                </span>
                            </div>

                            @if($plan->location)
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">📍 {{ $plan->location }}</p>
                            @endif

                            @if($plan->cost)
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">💰 {{ number_format($plan->cost, 2) }} €</p>
                            @endif

                            @if($plan->overall_avg)
                                <div class="flex items-center gap-1 mb-4">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Valoración:') }}</span>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-yellow-400">{{ $i <= round($plan->overall_avg) ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-semibold">{{ number_format($plan->overall_avg, 1) }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1 mb-4">
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400 italic">{{ __('Sin valorar') }}</span>
                                </div>
                            @endif

                            <div class="flex items-center gap-2 text-sm text-neutral-500 dark:text-neutral-400 mb-4">
                                <span>📸 {{ $plan->photos_count }}</span>
                                <span>⭐ {{ $plan->ratings_count }}</span>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-neutral-200 dark:border-neutral-700">
                                <flux:link href="{{ route('plans.show', $plan) }}" wire:navigate variant="ghost" size="sm">
                                    {{ __('Ver Detalle') }}
                                </flux:link>
                                <div class="flex gap-2">
                                    <flux:link href="{{ route('plans.edit', $plan) }}" wire:navigate variant="ghost" size="sm">
                                        {{ __('Editar') }}
                                    </flux:link>
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
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-12 text-center">
                    <div class="text-6xl mb-4">📅</div>
                    <h3 class="text-xl font-semibold text-neutral-900 dark:text-white mb-2">{{ __('No hay planes') }}</h3>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-6">{{ __('Comienza creando tu primer plan juntos') }}</p>
                    <flux:link href="{{ route('plans.create') }}" wire:navigate variant="primary">
                        {{ __('Crear Primer Plan') }}
                    </flux:link>
                </div>
            @endif
        </div>
    </x-layouts.app>

