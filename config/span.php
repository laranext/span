<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Span Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be load on demand with provided key
    | e.g. we are calling 'admin' then we will fetch AdminServiceProvider pkg
    | if you want to add prefix before key then register in prefix_providers
    |
    */

    'prefix' => 'prefix',

    'prefix_providers' => [
        // 'blog' => Blog\BlogServiceProvider::class,
    ],

    'providers' => [
        // '' => Site\SiteServiceProvider::class,
        // 'admin' => Admin\AdminServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Exclude these keys from laranext providers to avoid conflict.
    |--------------------------------------------------------------------------
    |
    */

    'excluded_routes' => [
        'login',
        'register',
        'forgot-password',
        'reset-password',
        'verify-email',
        'email',
        'confirm-password',
        'logout',
    ],

];
