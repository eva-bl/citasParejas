<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@citas.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        $this->command->info('Usuario administrador creado:');
        $this->command->info('Email: admin@citas.com');
        $this->command->info('Password: admin123');
        $this->command->warn('⚠️  IMPORTANTE: Cambia la contraseña después del primer login');
    }
}

