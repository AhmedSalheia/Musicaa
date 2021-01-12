<?php

return [
    'template'=>[
        'public' => [
            'loader'              => TEMPLATE_PATH['public'] . 'loader.php',
            'header'              => TEMPLATE_PATH['public'] . 'header.php',
            ':view'               => ':action_view',
            'footer'              => TEMPLATE_PATH['public'] . 'footer.php'
        ],
        'dashboard' => [
            'loader'              => TEMPLATE_PATH['dashboard'] . 'loader.php',
            'sidebar'             => TEMPLATE_PATH['dashboard'] . 'sidebar.php',
            'wrapperstart'        => TEMPLATE_PATH['dashboard'] . 'wrapperstart.php',
            'header'              => TEMPLATE_PATH['dashboard'] . 'header.php',
            ':view'               => ':action_view',
            'footer'              => TEMPLATE_PATH['dashboard'] . 'footer.php',
            'wrapperend'          => TEMPLATE_PATH['dashboard'] . 'wrapperend.php'
        ]
    ],

    'header'=>[
        'public' => [
            'css' => [
                'plugins' => CSS.'plugins.css',
                'style' => CSS.'style.css',
                'fontawsom' => 'https://use.fontawesome.com/releases/v5.11.2/css/all.css',
                'google' => 'https://fonts.googleapis.com/css?family=Raleway&display=swap',
                'swiper' => CSS.'swiper.min.css',
                'animate' => CSS.'animate.css'
            ],
            'js'  => [

            ]
        ],
        'dashboard' => [
            'css' => [
                'loader'    => CSS.'loader.css',
                'style'     => CSS.'style.css',
                'myStyle'   => CSS.'myStyle.css'
            ],
            'js'  => [

            ]
        ]
    ],

    'footer'=>[
        'public' => [
            'js' => [
                'JQuery' => JS.'jQuery3.4.1.js',
                'bootstrap' => JS.'bootstrap.bundle.min.js',
                'swiper' => JS.'swiper.min.js',
                'script' => JS.'script.js'
            ]
        ],
        'dashboard' => [
            'js' => [
                'vendor'    => JS.'vendor.js',
                'bundle'    => JS.'bundle.js',
                'myScript'  => JS.'myScript.js'
            ]
        ]
    ]
];
