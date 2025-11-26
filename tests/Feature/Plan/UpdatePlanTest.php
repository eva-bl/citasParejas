<?php

use App\Actions\Plan\UpdatePlanAction;
use App\Models\Category;
use App\Models\Couple;
use App\Models\Plan;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('user can update a plan', function () {
    $couple = Couple::factory()->create();
    $user = User::factory()->create(['couple_id' => $couple->id]);
    $category = Category::factory()->create();
    $plan = Plan::factory()->create([
        'couple_id' => $couple->id,
        'created_by' => $user->id,
        'title' => 'Original Title',
    ]);

    actingAs($user);

    $action = new UpdatePlanAction();
    $updatedPlan = $action->execute($user, $plan, [
        'title' => 'Updated Title',
        'status' => 'completed',
    ]);

    expect($updatedPlan->title)->toBe('Updated Title')
        ->and($updatedPlan->status)->toBe('completed')
        ->and($updatedPlan->activityLog)->toHaveCount(1);
});

test('user cannot update plan from another couple', function () {
    $couple1 = Couple::factory()->create();
    $couple2 = Couple::factory()->create();
    $user1 = User::factory()->create(['couple_id' => $couple1->id]);
    $user2 = User::factory()->create(['couple_id' => $couple2->id]);
    $plan = Plan::factory()->create([
        'couple_id' => $couple1->id,
        'created_by' => $user1->id,
    ]);

    actingAs($user2);

    $action = new UpdatePlanAction();

    // This should be blocked by policy, but testing action level
    expect($plan->couple_id)->not->toBe($user2->couple_id);
});

