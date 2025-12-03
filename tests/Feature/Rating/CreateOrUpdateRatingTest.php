<?php

namespace Tests\Feature\Rating;

use App\Actions\Rating\CreateOrUpdateRatingAction;
use App\Models\Couple;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrUpdateRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_rating_for_their_couple_plan(): void
    {
        $couple = Couple::factory()->create();
        $user = User::factory()->create(['couple_id' => $couple->id]);
        $plan = Plan::factory()->create(['couple_id' => $couple->id]);

        $action = app(CreateOrUpdateRatingAction::class);
        
        $rating = $action->execute($plan, $user, [
            'fun' => 5,
            'emotional_connection' => 4,
            'organization' => 5,
            'value_for_money' => 4,
            'overall' => 5,
            'comment' => 'Great plan!',
        ]);

        $this->assertDatabaseHas('ratings', [
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'fun' => 5,
            'overall' => 5,
        ]);

        $this->assertEquals(5, $rating->overall);
        $this->assertEquals('Great plan!', $rating->comment);
    }

    public function test_user_can_update_existing_rating(): void
    {
        $couple = Couple::factory()->create();
        $user = User::factory()->create(['couple_id' => $couple->id]);
        $plan = Plan::factory()->create(['couple_id' => $couple->id]);
        
        Rating::factory()->create([
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'overall' => 3,
        ]);

        $action = app(CreateOrUpdateRatingAction::class);
        
        $rating = $action->execute($plan, $user, [
            'fun' => 5,
            'emotional_connection' => 5,
            'organization' => 5,
            'value_for_money' => 5,
            'overall' => 5,
            'comment' => 'Updated!',
        ]);

        $this->assertEquals(5, $rating->overall);
        $this->assertEquals('Updated!', $rating->comment);
        
        // Should only have one rating
        $this->assertEquals(1, $plan->ratings()->count());
    }

    public function test_user_cannot_rate_plan_from_different_couple(): void
    {
        $couple1 = Couple::factory()->create();
        $couple2 = Couple::factory()->create();
        $user = User::factory()->create(['couple_id' => $couple1->id]);
        $plan = Plan::factory()->create(['couple_id' => $couple2->id]);

        $action = app(CreateOrUpdateRatingAction::class);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        
        $action->execute($plan, $user, [
            'fun' => 5,
            'emotional_connection' => 5,
            'organization' => 5,
            'value_for_money' => 5,
            'overall' => 5,
        ]);
    }

    public function test_plan_averages_are_calculated_after_rating(): void
    {
        $couple = Couple::factory()->create();
        $user1 = User::factory()->create(['couple_id' => $couple->id]);
        $user2 = User::factory()->create(['couple_id' => $couple->id]);
        $plan = Plan::factory()->create(['couple_id' => $couple->id]);

        $action = app(CreateOrUpdateRatingAction::class);

        // First rating
        $action->execute($plan, $user1, [
            'fun' => 5,
            'emotional_connection' => 4,
            'organization' => 5,
            'value_for_money' => 4,
            'overall' => 5,
        ]);

        $plan->refresh();
        $this->assertEquals(5.0, $plan->overall_avg);
        $this->assertEquals(1, $plan->ratings_count);

        // Second rating
        $action->execute($plan, $user2, [
            'fun' => 3,
            'emotional_connection' => 3,
            'organization' => 3,
            'value_for_money' => 3,
            'overall' => 3,
        ]);

        $plan->refresh();
        $this->assertEquals(4.0, $plan->overall_avg); // (5 + 3) / 2 = 4
        $this->assertEquals(2, $plan->ratings_count);
    }
}




