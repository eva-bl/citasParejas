<?php

namespace App\Providers;

use App\Models\Couple;
use App\Models\Photo;
use App\Models\Plan;
use App\Models\Rating;
use App\Observers\PhotoObserver;
use App\Observers\PlanObserver;
use App\Observers\RatingObserver;
use App\Policies\CouplePolicy;
use App\Policies\PhotoPolicy;
use App\Policies\PlanPolicy;
use App\Policies\RatingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Couple::class => CouplePolicy::class,
        Plan::class => PlanPolicy::class,
        Rating::class => RatingPolicy::class,
        Photo::class => PhotoPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Register observers
        Plan::observe(PlanObserver::class);
        Rating::observe(RatingObserver::class);
        Photo::observe(PhotoObserver::class);
    }
}
