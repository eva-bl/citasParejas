<?php

use App\Actions\Couple\CreateCoupleAction;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('user can create a couple', function () {
    $user = User::factory()->create();

    actingAs($user);

    $action = new CreateCoupleAction();
    $couple = $action->execute($user);

    expect($couple)->toBeInstanceOf(\App\Models\Couple::class)
        ->and($couple->join_code)->toBeString()
        ->and(strlen($couple->join_code))->toBe(12)
        ->and($user->fresh()->couple_id)->toBe($couple->id);
});

test('user cannot create a couple if already has one', function () {
    $user = User::factory()->create();
    $couple = \App\Models\Couple::factory()->create();
    $user->update(['couple_id' => $couple->id]);

    actingAs($user);

    $action = new CreateCoupleAction();

    expect(fn () => $action->execute($user))->toThrow(\Exception::class, 'User already belongs to a couple');
});





