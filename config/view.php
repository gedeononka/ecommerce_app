<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Paths where Laravel will look for your views (Blade templates).
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | Where compiled Blade templates are stored (usually in /storage).
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    /*
    |--------------------------------------------------------------------------
    | View Engine Resolver
    |--------------------------------------------------------------------------
    |
    | Laravel uses Blade as the default engine.
    |
    */

    'engine' => 'blade',

];
