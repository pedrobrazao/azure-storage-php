<?php

use App\Azure\Sas\AccountUrlFactory;
use App\Azure\Sas\UrlFactoryInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

return file_exists(__DIR__ . '/container.local.php')
    ? include __DIR__ . '/container.local.php'
    : [
        'settings' => include __DIR__ . '/settings.php',
        LoggerInterface::class => function(ContainerInterface $c) {
            $settings = $c->get('settings')['logger'];

            $logger = new Logger($settings['name']);
        
            $handler = new StreamHandler($settings['path'], $settings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Twig::class => function(ContainerInterface $c) {
            $settings = $c->get('settings')['twig'];

            return Twig::create($settings['path'], $settings['options']);
                },
                UrlFactoryInterface::class => function(ContainerInterface $c) {
                    $settings = $c->get('settings')['blob'];

                    return new AccountUrlFactory($settings['account'], $settings['container'], $settings['key']);
                },
    ];
