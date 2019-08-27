<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        //db settings
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'pmaskfk',
            'username' => 'root',
            'password' => 'K@12Pm@$18!!',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'tickets-sale',
            'path' => __DIR__ . '/../../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
