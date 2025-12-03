<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test 
                            {--name=Usuario Prueba : Nombre del usuario}
                            {--email=usuario@citas.com : Email del usuario}
                            {--password=usuario123 : Contraseña del usuario}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un usuario de prueba (no administrador)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');

        // Verificar si el usuario ya existe
        if (User::where('email', $email)->exists()) {
            $this->error("El usuario con email {$email} ya existe.");
            return Command::FAILURE;
        }

        // Crear el usuario
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'is_admin' => false,
        ]);

        $this->info('✅ Usuario de prueba creado exitosamente:');
        $this->line("   Nombre: {$user->name}");
        $this->line("   Email: {$user->email}");
        $this->line("   Password: {$password}");
        $this->line("   Admin: No");
        $this->newLine();
        $this->warn('⚠️  IMPORTANTE: Este usuario NO tiene pareja asignada.');
        $this->warn('   Deberás crear una pareja o unirte a una existente después de iniciar sesión.');

        return Command::SUCCESS;
    }
}



