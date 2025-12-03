<?php

use App\Actions\Plan\CreatePlanAction;
use App\Models\Category;
use App\Models\Couple;
use App\Models\Plan;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('user can create a plan', function () {
    $couple = Couple::factory()->create();
    $user = User::factory()->create(['couple_id' => $couple->id]);
    $category = Category::factory()->create();

    actingAs($user);

    $action = new CreatePlanAction();
    $plan = $action->execute($user, [
        'title' => 'Cena romántica',
        'date' => now()->addDays(7),
        'category_id' => $category->id,
        'location' => 'Restaurante El Jardín',
        'cost' => 50.00,
        'description' => 'Una cena especial',
        'status' => 'pending',
    ]);

    expect($plan)->toBeInstanceOf(Plan::class)
        ->and($plan->couple_id)->toBe($couple->id)
        ->and($plan->created_by)->toBe($user->id)
        ->and($plan->status)->toBe('pending')
        ->and($plan->activityLog)->toHaveCount(1);
});

test('user cannot create plan without couple', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    actingAs($user);

    $action = new CreatePlanAction();

    expect(fn () => $action->execute($user, [
        'title' => 'Test Plan',
        'date' => now(),
        'category_id' => $category->id,
    ]))->toThrow(\Exception::class, 'User must belong to a couple');
});




