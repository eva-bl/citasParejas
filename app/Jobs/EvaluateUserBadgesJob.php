<?php

namespace App\Jobs;

use App\Actions\Badge\CheckAndAssignBadgesAction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EvaluateUserBadgesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CheckAndAssignBadgesAction $checkBadges): void
    {
        $newlyAssignedBadges = $checkBadges->execute($this->user);
        
        // Aquí podrías enviar notificaciones si hay insignias nuevas
        // Por ahora solo las asignamos
    }
}



