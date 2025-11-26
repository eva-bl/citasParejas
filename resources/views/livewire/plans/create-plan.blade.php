<?php

use App\Actions\Plan\CreatePlanAction;
use App\Models\Category;
use App\Models\Plan;
use Livewire\Volt\Component;

new class extends Component
{
    public string $title = '';
    public string $date = '';
    public ?int $category_id = null;
    public ?string $location = null;
    public ?float $cost = null;
    public ?string $description = null;
    public string $status = 'pending';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'in:pending,completed'],
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser válida.',
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'cost.numeric' => 'El coste debe ser un número.',
            'cost.min' => 'El coste no puede ser negativo.',
        ];
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function createPlan()
    {
        $this->authorize('create', Plan::class);
        $this->validate();

        try {
            $plan = app(CreatePlanAction::class)->execute(
                auth()->user(),
                [
                    'title' => $this->title,
                    'date' => $this->date,
                    'category_id' => $this->category_id,
                    'location' => $this->location,
                    'cost' => $this->cost,
                    'description' => $this->description,
                    'status' => $this->status,
                ]
            );

            session()->flash('success', 'Plan creado exitosamente.');
            
            return $this->redirect(route('plans.show', $plan), navigate: true);
        } catch (\Exception $e) {
            $this->addError('plan', $e->getMessage());
        }
    }
}; ?>

<div>
    <x-layouts.app :title="__('Crear Plan')">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">{{ __('Crear Nuevo Plan') }}</h2>
                    <flux:link href="{{ route('plans.index') }}" wire:navigate>
                        {{ __('Cancelar') }}
                    </flux:link>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @error('plan')
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 rounded">
                        {{ $message }}
                    </div>
                @enderror

                <form wire:submit="createPlan" class="space-y-6">
                    <!-- Title -->
                    <flux:field>
                        <flux:label>{{ __('Título') }}</flux:label>
                        <flux:input wire:model="title" placeholder="{{ __('Ej: Cena romántica en el centro') }}" />
                        <flux:error name="title" />
                    </flux:field>

                    <!-- Date and Category -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>{{ __('Fecha') }}</flux:label>
                            <flux:input type="date" wire:model="date" />
                            <flux:error name="date" />
                        </flux:field>

                        <flux:field>
                            <flux:label>{{ __('Categoría') }}</flux:label>
                            <flux:select wire:model="category_id" placeholder="{{ __('Selecciona una categoría') }}">
                                <option value="">{{ __('Selecciona una categoría') }}</option>
                                @foreach(Category::orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->icon }} {{ $category->name }}
                                    </option>
                                @endforeach
                            </flux:select>
                            <flux:error name="category_id" />
                        </flux:field>
                    </div>

                    <!-- Location and Cost -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>{{ __('Ubicación') }} <span class="text-neutral-500 text-sm">({{ __('Opcional') }})</span></flux:label>
                            <flux:input wire:model="location" placeholder="{{ __('Ej: Restaurante El Jardín') }}" />
                            <flux:error name="location" />
                        </flux:field>

                        <flux:field>
                            <flux:label>{{ __('Coste') }} <span class="text-neutral-500 text-sm">({{ __('Opcional') }})</span></flux:label>
                            <flux:input type="number" wire:model="cost" step="0.01" min="0" placeholder="0.00" />
                            <flux:error name="cost" />
                        </flux:field>
                    </div>

                    <!-- Status -->
                    <flux:field>
                        <flux:label>{{ __('Estado') }}</flux:label>
                        <flux:select wire:model="status">
                            <option value="pending">{{ __('Pendiente') }}</option>
                            <option value="completed">{{ __('Completado') }}</option>
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>

                    <!-- Description -->
                    <flux:field>
                        <flux:label>{{ __('Descripción') }} <span class="text-neutral-500 text-sm">({{ __('Opcional') }})</span></flux:label>
                        <flux:textarea wire:model="description" rows="4" placeholder="{{ __('Describe el plan...') }}" />
                        <flux:error name="description" />
                    </flux:field>

                    <!-- Submit -->
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <flux:link href="{{ route('plans.index') }}" wire:navigate>
                            {{ __('Cancelar') }}
                        </flux:link>
                        <flux:button type="submit" variant="primary">
                            {{ __('Crear Plan') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </x-layouts.app>
</div>

