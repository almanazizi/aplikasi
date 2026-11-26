<?php

use App\Models\Admin;
use App\Models\AkunMahasiswa;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'mahasiswa'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
        'mahasiswa' => [
            'driver' => 'session',
            'provider' => 'mahasiswa',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'admin' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ],
        'mahasiswa' => [
            'driver' => 'eloquent',
            'model' => AkunMahasiswa::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
