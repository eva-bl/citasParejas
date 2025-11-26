<?php

namespace App\Observers;

use App\Models\Photo;

class PhotoObserver
{
    /**
     * Handle the Photo "created" event.
     */
    public function created(Photo $photo): void
    {
        // Update photos_count in plan
        $photo->plan->increment('photos_count');
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

