<?php

/**
 * Script de verificación rápida del setup
 * Ejecutar: php verificar_setup.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Verificando setup del Sprint 2...\n\n";

// Verificar migraciones
echo "1. Verificando migraciones...\n";
try {
    $tables = [
        'couples',
        'categories',
        'plans',
        'ratings',
        'photos',
        'badges',
        'user_badges',
        'user_plan_favorites',
        'plan_activity_log',
    ];

    $missing = [];
    foreach ($tables as $table) {
        if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
            $missing[] = $table;
        }
    }

    if (empty($missing)) {
        echo "   ✅ Todas las tablas existen\n";
    } else {
        echo "   ❌ Faltan tablas: " . implode(', ', $missing) . "\n";
        echo "   💡 Ejecuta: php artisan migrate\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Verificar modelos
echo "\n2. Verificando modelos...\n";
$models = [
    'App\Models\Couple',
    'App\Models\Category',
    'App\Models\Plan',
    'App\Models\Rating',
    'App\Models\Photo',
    'App\Models\Badge',
    'App\Models\UserBadge',
    'App\Models\PlanActivityLog',
];

$missingModels = [];
foreach ($models as $model) {
    if (!class_exists($model)) {
        $missingModels[] = $model;
    }
}

if (empty($missingModels)) {
    echo "   ✅ Todos los modelos existen\n";
} else {
    echo "   ❌ Faltan modelos: " . implode(', ', $missingModels) . "\n";
}

// Verificar Actions
echo "\n3. Verificando Actions...\n";
$actions = [
    'App\Actions\Couple\CreateCoupleAction',
    'App\Actions\Couple\JoinCoupleAction',
];

$missingActions = [];
foreach ($actions as $action) {
    if (!class_exists($action)) {
        $missingActions[] = $action;
    }
}

if (empty($missingActions)) {
    echo "   ✅ Todas las Actions existen\n";
} else {
    echo "   ❌ Faltan Actions: " . implode(', ', $missingActions) . "\n";
}

// Verificar Policies
echo "\n4. Verificando Policies...\n";
if (class_exists('App\Policies\CouplePolicy')) {
    echo "   ✅ CouplePolicy existe\n";
} else {
    echo "   ❌ CouplePolicy no existe\n";
}

// Verificar vistas
echo "\n5. Verificando vistas...\n";
$views = [
    'resources/views/livewire/couple/setup.blade.php',
    'resources/views/livewire/couple/create-couple.blade.php',
    'resources/views/livewire/couple/join-couple.blade.php',
    'resources/views/livewire/dashboard.blade.php',
];

$missingViews = [];
foreach ($views as $view) {
    if (!file_exists($view)) {
        $missingViews[] = $view;
    }
}

if (empty($missingViews)) {
    echo "   ✅ Todas las vistas existen\n";
} else {
    echo "   ❌ Faltan vistas: " . implode(', ', $missingViews) . "\n";
}

// Verificar seeders
echo "\n6. Verificando datos de seed...\n";
try {
    $categoriesCount = \App\Models\Category::count();
    $badgesCount = \App\Models\Badge::count();

    echo "   Categorías: $categoriesCount (esperado: 10)\n";
    echo "   Insignias: $badgesCount (esperado: 6)\n";

    if ($categoriesCount >= 10 && $badgesCount >= 6) {
        echo "   ✅ Datos de seed correctos\n";
    } else {
        echo "   ⚠️  Algunos datos faltan. Ejecuta: php artisan db:seed\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️  No se pudo verificar (tablas pueden no existir)\n";
}

// Verificar rutas
echo "\n7. Verificando rutas...\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $routeNames = [
        'couple.setup',
        'couple.create',
        'couple.join',
        'dashboard',
    ];

    $missingRoutes = [];
    foreach ($routeNames as $routeName) {
        try {
            $routes->getByName($routeName);
        } catch (\Exception $e) {
            $missingRoutes[] = $routeName;
        }
    }

    if (empty($missingRoutes)) {
        echo "   ✅ Todas las rutas están registradas\n";
    } else {
        echo "   ❌ Faltan rutas: " . implode(', ', $missingRoutes) . "\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️  No se pudo verificar rutas\n";
}

echo "\n✨ Verificación completada!\n";
echo "\n📝 Próximos pasos:\n";
echo "   1. Si faltan migraciones: php artisan migrate\n";
echo "   2. Si faltan datos: php artisan db:seed\n";
echo "   3. Inicia el servidor: php artisan serve\n";
echo "   4. Abre: http://localhost:8000\n";





