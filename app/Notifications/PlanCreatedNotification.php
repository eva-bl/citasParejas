<?php

namespace App\Notifications;

use App\Models\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class PlanCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Plan $plan
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
            'type' => 'plan_created',
            'plan_id' => $this->plan->id,
            'plan_title' => $this->plan->title,
            'plan_date' => $this->plan->date->format('d/m/Y'),
            'created_by' => $this->plan->createdBy->name,
            'message' => $this->plan->createdBy->name . ' ha creado un nuevo plan: ' . $this->plan->title,
            'url' => route('plans.show', $this->plan),
        ];
    }
}
