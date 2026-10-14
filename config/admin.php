<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Admin
    |--------------------------------------------------------------------------
    |
    | Credentials for the first admin account, created by AdminUserSeeder so a
    | freshly migrated server is reachable. Read through config() rather than
    | env() directly, because env() silently returns null once the config is
    | cached (php artisan config:cache).
    |
    | Leave ADMIN_PASSWORD unset to have the seeder generate one and print it.
    |
    */

    'bootstrap' => [
        'employee_id' => env('ADMIN_EMPLOYEE_ID') ?: 'ADMIN-0001',
        'name' => env('ADMIN_NAME') ?: 'System Administrator',
        'username' => env('ADMIN_USERNAME') ?: 'admin',
        'email' => env('ADMIN_EMAIL') ?: null,
        'password' => env('ADMIN_PASSWORD') ?: null,
    ],

];
