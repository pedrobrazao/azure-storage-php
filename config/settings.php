<?php

use Monolog\Logger;

return file_exists(__DIR__ . '/settings.local.php')
    ? include __DIR__ . '/settings.local.php'
: [
    'displayErrorDetails' => false, // Should be set to false in production
    'logger' => [
        'name' => 'app',
        'path' => 'php://stderr',
        'level' => Logger::DEBUG,
    ],
    'twig' => [
        'path' => __DIR__ . '/../templates',
        'options' => [
            'cache' => false,
        ],
    ],
];