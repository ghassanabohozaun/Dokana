<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default Flasher Service
    |--------------------------------------------------------------------------
    |
    | This option controls the default flasher service that will be used.
    |
    */
    'default' => 'flasher',

    /*
    |--------------------------------------------------------------------------
    | Main Script and Styles
    |--------------------------------------------------------------------------
    |
    | These options control the main script and styles that will be used.
    |
    */
    'main_script' => '/vendor/flasher/flasher.min.js',
    'styles' => [
        '/vendor/flasher/flasher.min.css',
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Options
    |--------------------------------------------------------------------------
    |
    | These options will be passed to the flasher service.
    |
    */
    'options' => [
        'position' => 'top-right',
        'timeout' => 5000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Flashers
    |--------------------------------------------------------------------------
    |
    | Here you can configure the flasher services.
    |
    */
    'flashers' => [
        'flasher' => [
            'scripts' => [
                '/vendor/flasher/flasher.min.js',
            ],
            'styles' => [
                '/vendor/flasher/flasher.min.css',
            ],
            'options' => [
                'position' => 'top-right',
            ],
        ],
    ],
];
