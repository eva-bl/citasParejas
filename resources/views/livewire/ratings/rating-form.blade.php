<?php

use App\Actions\Rating\CreateOrUpdateRatingAction;
use App\Models\Plan;
use Livewire\Volt\Component;

new class extends Component
{
    public Plan $plan;
    public int $fun = 3;
    public int $emotional_connection = 3;
    public int $organization = 3;
    public int $value_for_money = 3;
    public int $overall = 3;
    public ?string $comment = null;
    public bool $hasExistingRating = false;

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
        
        // Check if user already has a rating
        $existingRating = $plan->getRatingFrom(auth()->user());
        
        if ($existingRating) {
            $this->hasExistingRating = true;
            $this->fun = $existingRating->fun;
            $this->emotional_connection = $existingRating->emotional_connection;
            $this->organization = $existingRating->organization;
            $this->value_for_money = $existingRating->value_for_money;
            $this->overall = $existingRating->overall;
            $this->comment = $existingRating->comment;
        }
    }

    public function save()
    {
        $this->authorize('create', $this->plan);

        try {
            app(CreateOrUpdateRatingAction::class)->execute(
                $this->plan,
                auth()->user(),
                [
                    'fun' => $this->fun,
                    'emotional_connection' => $this->emotional_connection,
                    'organization' => $this->organization,
                    'value_for_money' => $this->value_for_money,
                    'overall' => $this->overall,
                    'comment' => $this->comment,
                ]
            );

            $this->hasExistingRating = true;
            $this->plan->refresh();
            
            session()->flash('rating_success', __('Valoración guardada exitosamente.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $key => $messages) {
                foreach ($messages as $message) {
                    $this->addError($key, $message);
                }
            }
        } catch (\Exception $e) {
            $this->addError('rating', $e->getMessage());
        }
    }
}; ?>

<div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
    <h2 class="text-xl font-semibold mb-4 text-neutral-900 dark:text-white">
        {{ $hasExistingRating ? __('Editar Mi Valoración') : __('Valorar Este Plan') }}
    </h2>

    @if(session('rating_success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 rounded">
            {{ session('rating_success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <!-- Fun Rating -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                {{ __('Diversión') }} <span class="text-pink-600">*</span>
            </label>
            <div class="flex items-center gap-4">
                <input 
                    type="range" 
                    wire:model.live="fun" 
                    min="1" 
                    max="5" 
                    step="1"
                    class="flex-1 h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-pink-500"
                >
                <div class="flex items-center gap-1 min-w-[120px]">
                    <span class="text-2xl font-bold text-pink-600">{{ $fun }}</span>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-yellow-400 text-lg">{{ $i <= $fun ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
            </div>
            @error('fun')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Emotional Connection Rating -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                {{ __('Conexión Emocional') }} <span class="text-pink-600">*</span>
            </label>
            <div class="flex items-center gap-4">
                <input 
                    type="range" 
                    wire:model.live="emotional_connection" 
                    min="1" 
                    max="5" 
                    step="1"
                    class="flex-1 h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-purple-500"
                >
                <div class="flex items-center gap-1 min-w-[120px]">
                    <span class="text-2xl font-bold text-purple-600">{{ $emotional_connection }}</span>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-yellow-400 text-lg">{{ $i <= $emotional_connection ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
            </div>
            @error('emotional_connection')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Organization Rating -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                {{ __('Organización') }} <span class="text-pink-600">*</span>
            </label>
            <div class="flex items-center gap-4">
                <input 
                    type="range" 
                    wire:model.live="organization" 
                    min="1" 
                    max="5" 
                    step="1"
                    class="flex-1 h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-blue-500"
                >
                <div class="flex items-center gap-1 min-w-[120px]">
                    <span class="text-2xl font-bold text-blue-600">{{ $organization }}</span>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-yellow-400 text-lg">{{ $i <= $organization ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
            </div>
            @error('organization')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Value for Money Rating -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                {{ __('Relación Calidad-Precio') }} <span class="text-pink-600">*</span>
            </label>
            <div class="flex items-center gap-4">
                <input 
                    type="range" 
                    wire:model.live="value_for_money" 
                    min="1" 
                    max="5" 
                    step="1"
                    class="flex-1 h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-green-500"
                >
                <div class="flex items-center gap-1 min-w-[120px]">
                    <span class="text-2xl font-bold text-green-600">{{ $value_for_money }}</span>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-yellow-400 text-lg">{{ $i <= $value_for_money ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
            </div>
            @error('value_for_money')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Overall Rating -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                {{ __('Valoración General') }} <span class="text-pink-600">*</span>
            </label>
            <div class="flex items-center gap-4">
                <input 
                    type="range" 
                    wire:model.live="overall" 
                    min="1" 
                    max="5" 
                    step="1"
                    class="flex-1 h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-pink-500"
                >
                <div class="flex items-center gap-1 min-w-[120px]">
                    <span class="text-2xl font-bold text-pink-600">{{ $overall }}</span>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-yellow-400 text-xl">{{ $i <= $overall ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
            </div>
            @error('overall')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Comment -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                {{ __('Comentario (opcional)') }}
            </label>
            <textarea 
                wire:model="comment" 
                rows="4"
                class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:bg-neutral-700 dark:text-white"
                placeholder="{{ __('Escribe tus comentarios sobre este plan...') }}"
            ></textarea>
            @error('comment')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @error('rating')
            <div class="p-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 rounded">
                {{ $message }}
            </div>
        @enderror

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">
                {{ $hasExistingRating ? __('Actualizar Valoración') : __('Guardar Valoración') }}
            </flux:button>
        </div>
    </form>
</div>




