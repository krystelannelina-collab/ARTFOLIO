<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public Contact Details
    |--------------------------------------------------------------------------
    |
    | Shown on the About page. Leave a value empty to show a "coming soon"
    | placeholder instead of inventing contact details.
    |
    */

    'contact' => [
        'email' => env('ARTFOLIO_CONTACT_EMAIL'),
        'phone' => env('ARTFOLIO_CONTACT_PHONE'),
        'facebook_url' => env('ARTFOLIO_FACEBOOK_URL'),
        'address' => env('ARTFOLIO_CONTACT_ADDRESS'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Initial Admin Account
    |--------------------------------------------------------------------------
    |
    | Used by "php artisan db:seed" to create the first administrator. No
    | password is stored in code: set ARTFOLIO_ADMIN_PASSWORD in your .env.
    | More admins can be added with "php artisan app:promote-admin {email}".
    |
    */

    'admin' => [
        'name' => env('ARTFOLIO_ADMIN_NAME', 'ARTFOLIO Admin'),
        'email' => env('ARTFOLIO_ADMIN_EMAIL', 'admin@artfolio.com'),
        'password' => env('ARTFOLIO_ADMIN_PASSWORD'),
    ],

];
