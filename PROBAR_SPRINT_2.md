# 🧪 Guía para Probar el Sprint 2

## 📋 Pasos para Probar

### 1. Ejecutar Migraciones

Abre una terminal en el directorio del proyecto y ejecuta:

```bash
php artisan migrate
```

O si usas Herd:
```bash
herd php artisan migrate
```

**Resultado esperado:** Deberías ver que se crean las siguientes tablas:
- couples
- categories
- plans
- ratings
- photos
- badges
- user_badges
- user_plan_favorites
- plan_activity_log
- Y se actualiza la tabla users con couple_id y avatar_path

### 2. Ejecutar Seeders

```bash
php artisan db:seed
```

O:
```bash
herd php artisan db:seed
```

**Resultado esperado:** Se crearán:
- 10 categorías (Cena, Cine, Aventura, etc.)
- 6 insignias (Primera Cita, Exploradores, etc.)

### 3. Verificar Usuario de Prueba

El seeder crea un usuario de prueba:
- **Email:** test@example.com
- **Password:** password

Si necesitas crear otro usuario, puedes usar Tinker:

```bash
php artisan tinker
```

Luego en Tinker:
```php
User::create([
    'name' => 'Usuario Prueba',
    'email' => 'prueba@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);
```

### 4. Iniciar el Servidor

```bash
php artisan serve
```

O si usas Herd, el servidor ya debería estar corriendo.

### 5. Probar el Flujo Completo

#### Paso 1: Acceder a la Aplicación
1. Abre tu navegador en: `http://localhost:8000` (o la URL de Herd)
2. Deberías ver la página de inicio

#### Paso 2: Registrarse o Iniciar Sesión
1. Haz clic en "Register" o "Login"
2. Si es la primera vez, regístrate con:
   - Name: Tu nombre
   - Email: tu@email.com
   - Password: password (mínimo 8 caracteres)
3. Si ya tienes cuenta, inicia sesión

#### Paso 3: Verificar Redirección
1. Después de login, deberías ser redirigido a `/dashboard`
2. Como no tienes pareja, deberías ser redirigido automáticamente a `/couple/setup`
3. Deberías ver dos opciones:
   - **Crear Pareja** (botón azul)
   - **Unirse a Pareja** (botón verde)

#### Paso 4: Crear una Pareja
1. Haz clic en "Crear Pareja"
2. Deberías ver un formulario con información
3. Haz clic en "Crear Pareja"
4. **Resultado esperado:**
   - Mensaje de éxito con el código de unión (12 caracteres)
   - Redirección a `/dashboard`
   - En el dashboard deberías ver:
     - Card "Mi Pareja" con estado "Activa"
     - Código de unión visible
     - Mensaje "Esperando a que tu pareja se una..."

#### Paso 5: Probar Unirse a Pareja (en otra sesión/navegador)
1. Abre una ventana de incógnito o otro navegador
2. Regístrate con otro usuario
3. Deberías ser redirigido a `/couple/setup`
4. Haz clic en "Unirse a Pareja"
5. Ingresa el código de unión que obtuviste en el Paso 4
6. Haz clic en "Unirse a Pareja"
7. **Resultado esperado:**
   - Mensaje de éxito
   - Redirección a `/dashboard`
   - En el dashboard deberías ver el nombre de tu pareja

#### Paso 6: Verificar Validaciones
1. Intenta crear otra pareja con el mismo usuario → Debería dar error
2. Intenta unirse con un código inválido → Debería dar error
3. Intenta unirse con un código de 11 caracteres → Debería validar formato

### 6. Ejecutar Tests

```bash
php artisan test --filter Couple
```

O:
```bash
herd php artisan test --filter Couple
```

**Resultado esperado:** Todos los tests deberían pasar:
- ✅ Usuario puede crear pareja
- ✅ Usuario no puede crear si ya tiene pareja
- ✅ Usuario puede unirse con código válido
- ✅ Usuario no puede unirse con código inválido
- ✅ Usuario no puede unirse si ya tiene pareja
- ✅ Usuario no puede unirse a pareja completa

## 🔍 Verificaciones Adicionales

### Verificar en Base de Datos

Puedes usar Tinker para verificar:

```bash
php artisan tinker
```

```php
// Ver todas las parejas
\App\Models\Couple::all();

// Ver usuarios con pareja
\App\Models\User::whereNotNull('couple_id')->get();

// Ver una pareja específica
$couple = \App\Models\Couple::first();
$couple->users; // Ver miembros de la pareja
$couple->join_code; // Ver código de unión
```

### Verificar Logs

Si hay errores, revisa:
```bash
tail -f storage/logs/laravel.log
```

## 🐛 Solución de Problemas

### Error: "Class 'Couple' not found"
- Ejecuta: `composer dump-autoload`

### Error: "Table doesn't exist"
- Ejecuta: `php artisan migrate:fresh` (⚠️ Esto borra todos los datos)

### Error: "Route not found"
- Ejecuta: `php artisan route:clear`
- Ejecuta: `php artisan route:cache`

### Error: "View not found"
- Verifica que los archivos estén en `resources/views/livewire/couple/`
- Ejecuta: `php artisan view:clear`

### El dashboard no redirige
- Verifica que el archivo `resources/views/dashboard.blade.php` haya sido eliminado
- Verifica que existe `resources/views/livewire/dashboard.blade.php`

## ✅ Checklist de Pruebas

- [ ] Migraciones ejecutadas correctamente
- [ ] Seeders ejecutados correctamente
- [ ] Puedo registrarme/iniciar sesión
- [ ] Soy redirigido a `/couple/setup` si no tengo pareja
- [ ] Puedo crear una pareja
- [ ] Veo el código de unión después de crear
- [ ] El dashboard muestra información de la pareja
- [ ] Puedo unirme a una pareja con código válido
- [ ] No puedo crear otra pareja si ya tengo una
- [ ] No puedo unirme con código inválido
- [ ] Los tests pasan correctamente

## 📝 Notas

- El código de unión es de 12 caracteres alfanuméricos en mayúsculas
- Una pareja puede tener máximo 2 miembros
- El join_code es único por pareja
- Los usuarios sin pareja no pueden acceder a funcionalidades que requieren pareja (esto se implementará en Sprint 3)


