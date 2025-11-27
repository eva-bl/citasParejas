<?php

namespace App\Observers;

use App\Models\Photo;
use App\Notifications\PhotosUploadedNotification;

class PhotoObserver
{
    /**
     * Handle the Photo "created" event.
     */
    public function created(Photo $photo): void
    {
        // Update photos_count in plan
        $photo->plan->increment('photos_count');
        
        // Notificar a la pareja (excepto al que subió)
        // Nota: La notificación se enviará desde UploadPlanPhotosAction después de subir todas las fotos
        // para evitar múltiples notificaciones
    }

    /**
     * Handle the Photo "deleted" event.
     */
    public function deleted(Photo $photo): void
    {
        // Update photos_count in plan
        $photo->plan->decrement('photos_count');
    }
}

