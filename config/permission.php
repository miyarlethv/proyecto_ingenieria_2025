<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modelos usados por Spatie Permission
    |--------------------------------------------------------------------------
    |
    | Aquí definimos los modelos que utilizará el paquete para manejar los
    | roles y permisos. Puedes modificar los modelos predeterminados si
    | deseas usar tus propias clases personalizadas.
    |
    */

    'models' => [

        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,

        /*
         * 🔹 Agregamos aquí nuestro modelo principal que usará los roles y permisos.
         * En tu caso será App\Models\Funcionario.
         */
        'user' => App\Models\Funcionario::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Nombres de tablas
    |--------------------------------------------------------------------------
    |
    | Aquí defines los nombres de las tablas que utilizará el paquete para
    | almacenar los roles, permisos y relaciones.
    |
    */

    'table_names' => [

        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Nombres de columnas
    |--------------------------------------------------------------------------
    |
    | Puedes personalizar los nombres de las columnas pivot si tus claves
    | primarias o relaciones tienen nombres distintos.
    |
    */

    'column_names' => [
        'role_pivot_key' => null, // por defecto: role_id
        'permission_pivot_key' => null, // por defecto: permission_id
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración general
    |--------------------------------------------------------------------------
    */

    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'events_enabled' => false,
    'teams' => false,
    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,

    /*
    |--------------------------------------------------------------------------
    | Configuración de caché
    |--------------------------------------------------------------------------
    |
    | Spatie Permission usa caché para mejorar el rendimiento.
    | Cuando se modifican los roles o permisos, el caché se limpia automáticamente.
    |
    */

    'cache' => [

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        'key' => 'spatie.permission.cache',

        'store' => 'default',
    ],
];
