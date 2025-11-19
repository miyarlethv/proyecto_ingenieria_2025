<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Funcionario;
use Illuminate\Support\Facades\DB;

echo "=== VERIFICACIÓN DE ROLES ===\n\n";

// Ver registros en model_has_roles
echo "Registros en model_has_roles:\n";
$modelHasRoles = DB::table('model_has_roles')->get();
foreach ($modelHasRoles as $rel) {
    echo "  role_id: {$rel->role_id}, model_type: {$rel->model_type}, model_id: {$rel->model_id}\n";
}

echo "\n";

// Ver funcionarios con roles
echo "Funcionarios con roles:\n";
$funcionarios = Funcionario::with('roles')->get();
foreach ($funcionarios as $func) {
    echo "  ID: {$func->id}, Nombre: {$func->nombre}, Roles: " . $func->roles->pluck('name')->implode(', ') . "\n";
}

echo "\n=== FIN ===\n";
