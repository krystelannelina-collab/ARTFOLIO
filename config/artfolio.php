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

];
