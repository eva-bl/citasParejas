<?php

use App\Actions\Photo\UploadPlanPhotosAction;
use App\Models\Plan;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public Plan $plan;
    public $photos = [];
    public $uploadedPhotos = [];
    public $uploading = false;
    public $uploadProgress = 0;

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
        $this->authorize('create', $plan);
    }

    public function upload()
    {
        $this->authorize('create', $this->plan);

        $this->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,webp|max:10240', // 10MB max
        ], [
            'photos.*.image' => __('Todos los archivos deben ser imágenes.'),
            'photos.*.mimes' => __('Las imágenes deben ser JPG, PNG o WebP.'),
            'photos.*.max' => __('Cada imagen no puede exceder 10MB.'),
        ]);

        if (empty($this->photos)) {
            return;
        }

        $this->uploading = true;
        $this->uploadProgress = 0;

        try {
            $action = app(UploadPlanPhotosAction::class);
            $uploaded = $action->execute($this->plan, $this->photos);
            
            $this->uploadedPhotos = array_merge($this->uploadedPhotos, $uploaded);
            $this->photos = [];
            $this->plan->refresh();
            
            session()->flash('photo_upload_success', __('Fotos subidas exitosamente.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $key => $messages) {
                foreach ($messages as $message) {
                    $this->addError($key, $message);
                }
            }
        } catch (\Exception $e) {
            $this->addError('photos', $e->getMessage());
        } finally {
            $this->uploading = false;
            $this->uploadProgress = 0;
        }
    }

    public function removePreview($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }
}; ?>

<div class="bg-white rounded-2xl border-2 border-dashed border-pink-300 p-6 hover:border-pink-500 transition-all"
     x-data="{ 
         isDragging: false,
         handleDragOver(e) {
             e.preventDefault();
             this.isDragging = true;
         },
         handleDragLeave() {
             this.isDragging = false;
         },
         handleDrop(e) {
             e.preventDefault();
             this.isDragging = false;
             // Note: Livewire file uploads need to be handled via input element
             // Drag and drop will be handled by clicking the input
         }
     }"
     @dragover="handleDragOver"
     @dragleave="handleDragLeave"
     @drop="handleDrop">
    
    <div class="text-center"
         :class="{ 'opacity-50': isDragging }">
        <div class="mb-4">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-pink-400 to-purple-500 rounded-2xl flex items-center justify-center mb-3">
                <span class="text-3xl">📸</span>
            </div>
            <h3 class="text-xl font-bold text-neutral-900 mb-2">{{ __('Subir Fotos') }}</h3>
            <p class="text-neutral-600 text-sm mb-4">
                {{ __('Arrastra y suelta imágenes aquí o haz clic para seleccionar') }}
            </p>
        </div>

        <div class="flex flex-col items-center gap-3">
            <label class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-semibold cursor-pointer hover:shadow-lg transition-all transform hover:scale-105">
                <span>{{ __('Seleccionar Fotos') }}</span>
                <input type="file" 
                       wire:model="photos" 
                       multiple 
                       accept="image/jpeg,image/png,image/webp"
                       class="hidden">
            </label>
            
            @if(!empty($photos))
                <button wire:click="upload" 
                        :disabled="$uploading"
                        class="px-6 py-3 bg-gradient-to-r from-purple-500 to-blue-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="upload">{{ __('Subir') }} {{ count($photos) }} {{ __('foto(s)') }}</span>
                    <span wire:loading wire:target="upload">{{ __('Subiendo...') }}</span>
                </button>
            @endif
        </div>

        @error('photos')
            <p class="mt-4 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('photos.*')
            <p class="mt-4 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if(session('photo_upload_success'))
            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('photo_upload_success') }}
            </div>
        @endif
    </div>

    <!-- Preview Grid -->
    @if(!empty($photos))
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($photos as $index => $photo)
                <div class="relative group rounded-xl overflow-hidden border-2 border-pink-200">
                    <img src="{{ $photo->temporaryUrl() }}" 
                         alt="Preview" 
                         class="w-full h-32 object-cover">
                    <button wire:click="removePreview({{ $index }})"
                            class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>

