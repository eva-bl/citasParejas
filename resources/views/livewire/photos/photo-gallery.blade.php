<?php

use App\Actions\Photo\DeletePlanPhotoAction;
use App\Models\Plan;
use App\Models\Photo;
use Livewire\Volt\Component;

new class extends Component
{
    public Plan $plan;
    public ?int $selectedPhotoId = null;
    public bool $showDeleteModal = false;
    public ?Photo $photoToDelete = null;

    public function mount(Plan $plan)
    {
        $this->plan = $plan->load('photos');
        $this->authorize('viewAny', $plan);
    }

    public function openLightbox($photoId)
    {
        $this->selectedPhotoId = $photoId;
    }

    public function closeLightbox()
    {
        $this->selectedPhotoId = null;
    }

    public function nextPhoto()
    {
        $photos = $this->plan->photos;
        $currentIndex = $photos->search(fn($photo) => $photo->id === $this->selectedPhotoId);
        
        if ($currentIndex !== false && $currentIndex < $photos->count() - 1) {
            $this->selectedPhotoId = $photos[$currentIndex + 1]->id;
        }
    }

    public function previousPhoto()
    {
        $photos = $this->plan->photos;
        $currentIndex = $photos->search(fn($photo) => $photo->id === $this->selectedPhotoId);
        
        if ($currentIndex !== false && $currentIndex > 0) {
            $this->selectedPhotoId = $photos[$currentIndex - 1]->id;
        }
    }

    public function confirmDelete(Photo $photo)
    {
        $this->authorize('delete', $photo);
        $this->photoToDelete = $photo;
        $this->showDeleteModal = true;
    }

    public function deletePhoto()
    {
        if (!$this->photoToDelete) {
            return;
        }

        $this->authorize('delete', $this->photoToDelete);

        try {
            app(DeletePlanPhotoAction::class)->execute($this->photoToDelete);
            $this->plan->refresh();
            $this->showDeleteModal = false;
            $this->photoToDelete = null;
            
            session()->flash('photo_delete_success', __('Foto eliminada exitosamente.'));
        } catch (\Exception $e) {
            $this->addError('photo', $e->getMessage());
            $this->showDeleteModal = false;
        }
    }
}; ?>

<div class="space-y-6"
     x-data="{ 
         lightboxOpen: @entangle('selectedPhotoId'),
         init() {
             this.$watch('lightboxOpen', (value) => {
                 if (value) {
                     document.body.style.overflow = 'hidden';
                 } else {
                     document.body.style.overflow = '';
                 }
             });
         }
     }">
    
    @if($plan->photos->count() > 0)
        <!-- Gallery Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($plan->photos as $photo)
                <div class="group relative aspect-square rounded-xl overflow-hidden border-2 border-pink-200 hover:border-pink-500 transition-all cursor-pointer"
                     wire:click="openLightbox({{ $photo->id }})">
                    <img src="{{ $photo->getThumbnailUrl('medium') }}" 
                         alt="Foto del plan"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    
                    <!-- Overlay on hover -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="absolute bottom-2 right-2">
                            <button wire:click.stop="confirmDelete($photo)"
                                    class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors"
                                    onclick="event.stopPropagation()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-20 h-20 mx-auto bg-gradient-to-br from-pink-100 to-purple-100 rounded-2xl flex items-center justify-center mb-4">
                <span class="text-4xl">📷</span>
            </div>
            <p class="text-neutral-600 text-lg">{{ __('Aún no hay fotos en este plan') }}</p>
            <p class="text-neutral-500 text-sm mt-2">{{ __('Sube fotos para recordar este momento especial') }}</p>
        </div>
    @endif

    <!-- Lightbox -->
    @if($selectedPhotoId)
        @php
            $selectedPhoto = $plan->photos->firstWhere('id', $selectedPhotoId);
        @endphp
        @if($selectedPhoto)
            <div class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center p-4"
                 x-show="lightboxOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 @click.self="lightboxOpen = false; $wire.closeLightbox()"
                 @keydown.escape.window="lightboxOpen = false; $wire.closeLightbox()"
                 @keydown.arrow-right.window="$wire.nextPhoto()"
                 @keydown.arrow-left.window="$wire.previousPhoto()">
                
                <!-- Close Button -->
                <button @click="lightboxOpen = false; $wire.closeLightbox()"
                        class="absolute top-4 right-4 w-12 h-12 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Previous Button -->
                @if($plan->photos->count() > 1)
                    <button @click="$wire.previousPhoto()"
                            class="absolute left-4 w-12 h-12 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Next Button -->
                    <button @click="$wire.nextPhoto()"
                            class="absolute right-4 w-12 h-12 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endif

                <!-- Image -->
                <div class="max-w-7xl max-h-full">
                    <img src="{{ $selectedPhoto->full_url }}" 
                         alt="Foto del plan"
                         class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
                </div>

                <!-- Photo Counter -->
                @if($plan->photos->count() > 1)
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-white/20 backdrop-blur-md rounded-full text-white text-sm">
                        {{ $plan->photos->search(fn($p) => $p->id === $selectedPhotoId) + 1 }} / {{ $plan->photos->count() }}
                    </div>
                @endif
            </div>
        @endif
    @endif

    <!-- Delete Modal -->
    <div x-show="$wire.showDeleteModal"
         x-transition
         class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl"
             @click.stop>
            <h3 class="text-xl font-bold text-neutral-900 mb-4">{{ __('Eliminar Foto') }}</h3>
            <p class="text-neutral-600 mb-6">{{ __('¿Estás seguro de que quieres eliminar esta foto? Esta acción no se puede deshacer.') }}</p>
            
            @error('photo')
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex items-center justify-end gap-4">
                <button wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
                    {{ __('Cancelar') }}
                </button>
                <button wire:click="deletePhoto"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    {{ __('Eliminar') }}
                </button>
            </div>
        </div>
    </div>

    @if(session('photo_delete_success'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('photo_delete_success') }}
        </div>
    @endif
</div>



