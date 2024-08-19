#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Application;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/vendor/autoload.php';
$dotenv = new Dotenv('APP_ENV', 'APP_DEBUG');

$dotenv->load(__DIR__.'/.env', __DIR__.'/.env.dev');
$container = new ContainerBuilder();
foreach ($_ENV as $env => $value) {
    $container->setParameter($env, $value);
}

$loader = new YamlFileLoader($container, new FileLocator());

$loader->load(__DIR__.'/config/services.yml');

$container->compile();

exit($container->get(Application::class)->run());
