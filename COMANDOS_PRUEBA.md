# 🚀 Comandos Rápidos para Probar Sprint 2

## Comandos Esenciales

### 1. Verificar Setup (Recomendado primero)
```bash
php verificar_setup.php
```
Este script verifica que todo esté en su lugar antes de probar.

### 2. Ejecutar Migraciones
```bash
php artisan migrate
```

### 3. Ejecutar Seeders
```bash
php artisan db:seed
```

### 4. Limpiar Caché (si hay problemas)
```bash
php artisan route:clear
php artisan view:clear
php artisan config:clear
composer dump-autoload
```

### 5. Iniciar Servidor
```bash
php artisan serve
```
Luego abre: http://localhost:8000

### 6. Ejecutar Tests
```bash
php artisan test --filter Couple
```

## Si usas Herd

Reemplaza `php artisan` con `herd php artisan` o simplemente usa Herd normalmente.

## Flujo de Prueba Rápido

1. **Ejecutar migraciones y seeders:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

2. **Iniciar servidor:**
   ```bash
   php artisan serve
   ```

3. **Abrir navegador:**
   - Ir a: http://localhost:8000
   - Registrarse o iniciar sesión
   - Deberías ser redirigido a `/couple/setup`

4. **Crear pareja:**
   - Clic en "Crear Pareja"
   - Clic en "Crear Pareja" (botón)
   - Copiar el código de unión

5. **Unirse a pareja (otra ventana/navegador):**
   - Registrarse con otro usuario
   - Clic en "Unirse a Pareja"
   - Pegar el código
   - Clic en "Unirse a Pareja"

6. **Verificar dashboard:**
   - Deberías ver información de la pareja
   - Ver el nombre de tu pareja

## Verificar en Base de Datos

```bash
php artisan tinker
```

Luego:
```php
// Ver parejas
\App\Models\Couple::all();

// Ver usuarios con pareja
\App\Models\User::with('couple')->whereNotNull('couple_id')->get();

// Ver una pareja específica
$couple = \App\Models\Couple::first();
$couple->users;
$couple->join_code;
```




