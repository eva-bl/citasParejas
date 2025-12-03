# 👤 Crear Usuario Administrador

## Opción 1: Usar el Seeder (Recomendado)

El seeder crea automáticamente un usuario administrador cuando ejecutas:

```bash
php artisan db:seed --class=AdminUserSeeder
```

O simplemente:

```bash
php artisan db:seed
```

**Credenciales por defecto:**
- **Email:** admin@citas.com
- **Password:** admin123

⚠️ **IMPORTANTE:** Cambia la contraseña después del primer login.

## Opción 2: Usar el Comando Artisan

Puedes crear un administrador personalizado con:

```bash
php artisan admin:create
```

O con opciones personalizadas:

```bash
php artisan admin:create --email=admin@example.com --name="Mi Admin" --password=miPassword123
```

Si no proporcionas contraseña, se generará una aleatoria que se mostrará en la consola.

## Opción 3: Crear Manualmente con Tinker

```bash
php artisan tinker
```

Luego:

```php
User::create([
    'name' => 'Administrador',
    'email' => 'admin@citas.com',
    'password' => bcrypt('admin123'),
    'email_verified_at' => now(),
    'is_admin' => true,
]);
```

## Verificar que es Admin

```bash
php artisan tinker
```

```php
$user = User::where('email', 'admin@citas.com')->first();
$user->isAdmin(); // Debe retornar true
$user->is_admin; // Debe ser 1 o true
```

## Notas

- El campo `is_admin` es un booleano
- Los administradores pueden tener o no tener pareja (couple_id puede ser null)
- El middleware de admin se implementará en el Sprint 6 con Filament



