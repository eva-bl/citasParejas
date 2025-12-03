<?php

use App\Actions\Couple\JoinCoupleAction;
use App\Models\Couple;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('user can join a couple with valid join code', function () {
    $couple = Couple::factory()->create();
    $user1 = User::factory()->create(['couple_id' => $couple->id]);
    $user2 = User::factory()->create();

    actingAs($user2);

    $action = new JoinCoupleAction();
    $joinedCouple = $action->execute($user2, $couple->join_code);

    expect($joinedCouple->id)->toBe($couple->id)
        ->and($user2->fresh()->couple_id)->toBe($couple->id);
});

test('user cannot join with invalid join code', function () {
    $user = User::factory()->create();

    actingAs($user);

    $action = new JoinCoupleAction();

    expect(fn () => $action->execute($user, 'INVALIDCODE'))->toThrow(\Exception::class, 'Invalid join code');
});

test('user cannot join if already has a couple', function () {
    $couple1 = Couple::factory()->create();
    $couple2 = Couple::factory()->create();
    $user = User::factory()->create(['couple_id' => $couple1->id]);

    actingAs($user);

    $action = new JoinCoupleAction();

    expect(fn () => $action->execute($user, $couple2->join_code))->toThrow(\Exception::class, 'User already belongs to a couple');
});

test('user cannot join a couple that is already complete', function () {
    $couple = Couple::factory()->create();
    $user1 = User::factory()->create(['couple_id' => $couple->id]);
    $user2 = User::factory()->create(['couple_id' => $couple->id]);
    $user3 = User::factory()->create();

    actingAs($user3);

    $action = new JoinCoupleAction();

    expect(fn () => $action->execute($user3, $couple->join_code))->toThrow(\Exception::class, 'This couple is already complete');
});





