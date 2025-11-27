<?php

namespace App\Notifications;

use App\Models\Plan;
use App\Models\Rating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PlanRatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Plan $plan,
        public Rating $rating
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
        return [
            'type' => 'plan_rated',
            'plan_id' => $this->plan->id,
            'plan_title' => $this->plan->title,
            'rating_id' => $this->rating->id,
            'rated_by' => $this->rating->user->name,
            'overall_rating' => $this->rating->overall,
            'message' => $this->rating->user->name . ' ha valorado el plan "' . $this->plan->title . '" con ' . $this->rating->overall . '/5',
            'url' => route('plans.show', $this->plan),
        ];
    }
}
