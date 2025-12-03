<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Volt::route('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Couple routes
    Volt::route('couple/setup', 'couple.setup')->name('couple.setup');
    Volt::route('couple/create', 'couple.create-couple')->name('couple.create');
    Volt::route('couple/{couple}/code', 'couple.couple-code')->name('couple.code');
    Volt::route('couple/join', 'couple.join-couple')->name('couple.join');

    // Plans routes
    Volt::route('plans', 'plans.plans-list')->name('plans.index');
    Volt::route('plans/create', 'plans.create-plan')->name('plans.create');
    Volt::route('plans/{plan}', 'plans.plan-detail')->name('plans.show');
    Volt::route('plans/{plan}/edit', 'plans.edit-plan')->name('plans.edit');
    Volt::route('plans/calendar', 'plans.plans-calendar')->name('plans.calendar');
    Volt::route('plans/favorites', 'plans.favorite-plans-list')->name('plans.favorites');

    // Statistics routes
    Volt::route('statistics', 'statistics.couple-stats-dashboard')->name('statistics.index');

    // Badges routes
    Volt::route('badges', 'badges.index')->name('badges.index');
    Volt::route('badges/couple', 'badges.couple-badges-display')->name('badges.couple');

    // Export routes
    Route::post('export/pdf', function () {
        $action = app(\App\Actions\Export\ExportPlansToPdfAction::class);
        $filePath = $action->execute(auth()->user()->couple);
        return response()->download($filePath)->deleteFileAfterSend();
    })->name('export.pdf');
    
    Route::post('export/csv', function () {
        $action = app(\App\Actions\Export\ExportPlansToCsvAction::class);
        $filePath = $action->execute(auth()->user()->couple);
        return response()->download($filePath)->deleteFileAfterSend();
    })->name('export.csv');
});
