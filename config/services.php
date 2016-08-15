<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    // 'mailgun' => [
    //     'domain' => '',
    //     'secret' => '',
    // ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key' => '',
        'secret' => '',
    ],

    'facebook' => [
        'client_id' => '1280081208683255',
        'client_secret' => '3b82465a1be63980716c7667f0b5a8de',
        'redirect' => 'http://djmaker.fr/auth/facebook/callback',
    ],

    'google' => [
        'client_id' => '1046404987199-giqi60goilj4v6ela0gru2sjpd1qfs7f.apps.googleusercontent.com',
        'client_secret' => 'fzjhyyZ4lJcOo1HUcrq7n6Hu',
        'redirect' => 'http://djmaker.fr/auth/google/callback',
    ],

];
