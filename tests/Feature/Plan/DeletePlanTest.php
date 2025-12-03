<?php

use App\Actions\Plan\DeletePlanAction;
use App\Models\Couple;
use App\Models\Plan;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('user can delete a plan', function () {
    $couple = Couple::factory()->create();
    $user = User::factory()->create(['couple_id' => $couple->id]);
    $plan = Plan::factory()->create([
        'couple_id' => $couple->id,
        'created_by' => $user->id,
    ]);

    actingAs($user);

    $action = new DeletePlanAction();
    $result = $action->execute($user, $plan);

    // Recuperar el plan incluyendo los soft-deleted
    $deletedPlan = \App\Models\Plan::withTrashed()->find($plan->id);

    expect($result)->toBeTrue()
    ->and($deletedPlan)->not->toBeNull()
    ->and($deletedPlan->deleted_at)->not->toBeNull()
    ->and($plan->activityLog()->where('action', 'deleted')->exists())->toBeTrue();
});




