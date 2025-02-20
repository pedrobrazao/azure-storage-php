<?php

use Monolog\Logger;

$settings = [
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
    'blob' => [
        'account' => 'STORAGE ACCOUNT NAME',
        'container' => 'CONTAINER NAME',
        'key' => 'SHARED ACCOUNT KEY',
    ],
];

$localFile = __DIR__ . '/settings.local.php';

if (!file_exists($localFile)) {
    return $settings;
}

return array_merge($settings, include $localFile);