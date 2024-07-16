#!/usr/bin/env php
<?php

use Symfony\Component\Config\FileLocator;
use App\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/vendor/autoload.php';
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/.env');


$container = new ContainerBuilder();

$loader = new YamlFileLoader($container, new FileLocator());

$loader->load(__DIR__.'/config/services.yml');

$container->compile();

exit($container->get(Application::class)->run());

