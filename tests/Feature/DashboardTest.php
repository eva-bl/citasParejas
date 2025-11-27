<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('authenticated users without couple are redirected to couple setup', function () {
    // Usuario autenticado (puedes añadir email_verified_at si lo necesitas)
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    // 👇 Ajusta esto si tu redirect es a otra ruta
    $response->assertRedirect(route('couple.setup'));
});
