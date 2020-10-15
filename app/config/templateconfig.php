<?php

return [
    'template'=>[
        'loader'              => TEMPLATE_PATH . 'loader.php',
        'header'              => TEMPLATE_PATH . 'header.php',
        ':view'               => ':action_view',
        'footer'              => TEMPLATE_PATH . 'footer.php',

    ],

    'header'=>[
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

    'footer'=>[
        'js' => [
            'JQuery' => JS.'jQuery3.4.1.js',
            'bootstrap' => JS.'bootstrap.bundle.min.js',
            'swiper' => JS.'swiper.min.js',
            'script' => JS.'script.js'
        ]
    ]
];