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
    Volt::route('couple/join', 'couple.join-couple')->name('couple.join');

    // Plans routes
    Volt::route('plans', 'plans.plans-list')->name('plans.index');
    Volt::route('plans/create', 'plans.create-plan')->name('plans.create');
    Volt::route('plans/{plan}', 'plans.plan-detail')->name('plans.show');
    Volt::route('plans/{plan}/edit', 'plans.edit-plan')->name('plans.edit');

    // Statistics routes
    Volt::route('statistics', 'statistics.couple-stats-dashboard')->name('statistics.index');
});
