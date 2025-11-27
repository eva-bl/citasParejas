<?php

namespace App\Notifications;

use App\Models\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PhotosUploadedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Plan $plan,
        public int $photosCount
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = $this->photosCount === 1 
            ? 'Se ha subido 1 foto al plan "' . $this->plan->title . '"'
            : 'Se han subido ' . $this->photosCount . ' fotos al plan "' . $this->plan->title . '"';

        return [
            'type' => 'photos_uploaded',
            'plan_id' => $this->plan->id,
            'plan_title' => $this->plan->title,
            'photos_count' => $this->photosCount,
            'uploaded_by' => auth()->user()->name,
            'message' => $message,
            'url' => route('plans.show', $this->plan),
        ];
    }
}
