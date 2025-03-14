<?php

use  AzureOss\Storage\Blob\BlobServiceClient;
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
                BlobServiceClient::class => function(ContainerInterface $c) {
                    $settings = $c->get('settings')['blob'];

                    $dsn = sprintf(
                        'DefaultEndpointsProtocol=%s;AccountName=%s;AccountKey=%s;BlobEndpoint=%s;',
                        $settings['protocol'],
                        $settings['accountName'],
                        $settings['accountKey'],
                        $settings['endpoint']
                    );
                   
                    return BlobServiceClient::fromConnectionString($dsn);
                },
    ];
