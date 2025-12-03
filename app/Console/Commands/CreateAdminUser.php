<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--email=admin@citas.com : Email del administrador}
                            {--name=Administrador : Nombre del administrador}
                            {--password= : Contraseña (si no se proporciona, se generará una aleatoria)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email');
        $name = $this->option('name');
        $password = $this->option('password') ?? $this->generateRandomPassword();

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("El usuario con email {$email} ya existe.");
            
            if ($this->confirm('¿Deseas actualizarlo a administrador?', false)) {
                $user = User::where('email', $email)->first();
                $user->update(['is_admin' => true]);
                $this->info("Usuario {$email} actualizado a administrador.");
                return Command::SUCCESS;
            }
            
            return Command::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        $this->info('Usuario administrador creado exitosamente!');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Email', $email],
                ['Nombre', $name],
                ['Contraseña', $password],
                ['Es Admin', 'Sí'],
            ]
        );

        $this->warn('⚠️  IMPORTANTE: Guarda esta contraseña de forma segura y cámbiala después del primer login.');

        return Command::SUCCESS;
    }

    /**
     * Generate a random password
     */
    protected function generateRandomPassword(): string
    {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 12);
    }
}




