<?php

use App\Actions\Couple\CreateCoupleAction;
use App\Models\Couple;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public int $member_count = 2;
    public $photo = null;
    public $photoPreview = null;

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,webp|max:10240', // 10MB max
        ], [
            'photo.image' => __('El archivo debe ser una imagen.'),
            'photo.mimes' => __('La imagen debe ser JPG, PNG o WebP.'),
            'photo.max' => __('La imagen no puede exceder 10MB.'),
        ]);

        if ($this->photo) {
            $this->photoPreview = $this->photo->temporaryUrl();
        }
    }

    public function removePhoto()
    {
        $this->photo = null;
        $this->photoPreview = null;
    }

    public function createCouple()
    {
        $this->authorize('create', Couple::class);

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'member_count' => 'required|integer|min:2|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,webp|max:10240',
        ], [
            'name.required' => __('El nombre de la pareja es obligatorio.'),
            'name.max' => __('El nombre no puede exceder 255 caracteres.'),
            'member_count.required' => __('Debes indicar cuántos sois en la pareja.'),
            'member_count.min' => __('Debe haber al menos 2 personas en la pareja.'),
            'member_count.max' => __('El número máximo de personas es 10.'),
            'photo.image' => __('El archivo debe ser una imagen.'),
            'photo.mimes' => __('La imagen debe ser JPG, PNG o WebP.'),
            'photo.max' => __('La imagen no puede exceder 10MB.'),
        ]);

        try {
            $couple = app(CreateCoupleAction::class)->execute(
                auth()->user(),
                $validated['name'],
                $validated['member_count'],
                $this->photo
            );

            session()->flash('success', __('Pareja creada exitosamente. Comparte este código con tu pareja: :code', ['code' => $couple->join_code]));
            
            return $this->redirect(route('dashboard'), navigate: true);
        } catch (\Exception $e) {
            $this->addError('couple', $e->getMessage());
        }
    }
}; ?>

<div>
    <x-layouts.app :title="__('Crear Pareja')">
        <div class="min-h-screen flex items-center justify-center py-12 px-4">
            <div class="max-w-2xl w-full">
                <div class="relative overflow-hidden rounded-3xl bg-white p-8 md:p-10 shadow-2xl"
                     x-data="{ 
                         show: false,
                         init() {
                             setTimeout(() => this.show = true, 100);
                         }
                     }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl mb-4 shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-3">{{ __('Crear una nueva pareja') }}</h2>
                        <p class="text-neutral-600 text-lg">
                            {{ __('Crea una nueva pareja y obtendrás un código único para compartir con tu pareja.') }}
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mb-6 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-green-800 font-semibold mb-1">{{ __('¡Éxito!') }}</p>
                                    <p class="text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @error('couple')
                        <div class="mb-6 p-5 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-200 rounded-2xl">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-red-800 font-semibold mb-1">{{ __('Error') }}</p>
                                    <p class="text-red-700">{{ $message }}</p>
                                </div>
                            </div>
                        </div>
                    @enderror

                    <form wire:submit="createCouple" class="space-y-6">
                        <!-- Nombre de la pareja -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-purple-700 mb-2">
                                {{ __('Nombre de la pareja') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   wire:model="name"
                                   required
                                   placeholder="{{ __('Ej: Mi Pareja Perfecta') }}"
                                   class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Número de personas -->
                        <div>
                            <label for="member_count" class="block text-sm font-medium text-purple-700 mb-2">
                                {{ __('¿Cuántos sois en la pareja?') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="member_count"
                                    wire:model="member_count"
                                    required
                                    class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-purple-700 @error('member_count') border-red-500 @enderror">
                                @for($i = 2; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i === 2 ? __('personas') : __('personas') }}</option>
                                @endfor
                            </select>
                            @error('member_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto de la pareja -->
                        <div>
                            <label class="block text-sm font-medium text-purple-700 mb-2">
                                {{ __('Foto de la pareja') }} <span class="text-neutral-500 text-xs">({{ __('Opcional') }})</span>
                            </label>
                            
                            @if($photoPreview)
                                <div class="mb-4 relative">
                                    <img src="{{ $photoPreview }}" 
                                         alt="{{ __('Vista previa de la foto') }}"
                                         class="w-full h-64 object-cover rounded-lg border-2 border-purple-200">
                                    <button type="button"
                                            wire:click="removePhoto"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                                            title="{{ __('Quitar foto') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <div class="border-2 border-dashed border-purple-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors"
                                     x-data="{ 
                                         isDragging: false,
                                         handleDrop(e) {
                                             e.preventDefault();
                                             this.isDragging = false;
                                             if (e.dataTransfer.files.length > 0) {
                                                 @this.upload('photo', e.dataTransfer.files[0]);
                                             }
                                         },
                                         handleDragOver(e) {
                                             e.preventDefault();
                                             this.isDragging = true;
                                         },
                                         handleDragLeave() {
                                             this.isDragging = false;
                                         }
                                     }"
                                     @drop.prevent="handleDrop($event)"
                                     @dragover.prevent="handleDragOver($event)"
                                     @dragleave.prevent="handleDragLeave()"
                                     :class="{ 'border-purple-500 bg-purple-50': isDragging }">
                                    <input type="file"
                                           wire:model="photo"
                                           accept="image/jpeg,image/png,image/webp"
                                           class="hidden"
                                           id="photo-upload">
                                    <label for="photo-upload" class="cursor-pointer">
                                        <svg class="w-12 h-12 text-purple-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-purple-700 font-medium mb-1">{{ __('Haz clic para subir una foto') }}</p>
                                        <p class="text-sm text-neutral-500">{{ __('o arrastra y suelta aquí') }}</p>
                                        <p class="text-xs text-neutral-400 mt-2">{{ __('JPG, PNG o WebP (máx. 10MB)') }}</p>
                                    </label>
                                </div>
                            @endif
                            
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @if($photo)
                                <div wire:loading wire:target="photo" class="mt-2 text-sm text-purple-600">
                                    {{ __('Subiendo foto...') }}
                                </div>
                            @endif
                        </div>

                        <div class="bg-gradient-to-r from-pink-50 via-purple-50 to-blue-50 border-2 border-pink-200 rounded-2xl p-6">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">💡</span>
                                <div>
                                    <p class="text-neutral-800 font-semibold mb-1">{{ __('¿Cómo funciona?') }}</p>
                                    <p class="text-neutral-700 text-sm">
                                        {{ __('Al crear una pareja, se generará un código único de 12 caracteres. Comparte este código con tu pareja para que se una a tu pareja.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('couple.setup') }}" 
                               wire:navigate
                               class="px-6 py-3 text-neutral-700 hover:text-neutral-900 font-semibold rounded-xl hover:bg-neutral-100 transition-all">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold rounded-xl hover:shadow-xl transition-all transform hover:scale-105">
                                <span wire:loading.remove wire:target="createCouple">{{ __('Crear Pareja') }}</span>
                                <span wire:loading wire:target="createCouple" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('Creando...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-layouts.app>
</div>
