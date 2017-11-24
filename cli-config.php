<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';
$dotEnv = new Dotenv(__DIR__.'/src');
$dotEnv->load();
$dotEnv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

$settings = require __DIR__ . '/src/settings.php';
$doctrine = $settings['settings']['doctrine'];

$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $doctrine['meta']['entity_path'],
    $doctrine['meta']['auto_generate_proxies'],
    $doctrine['meta']['proxy_dir'],
    $doctrine['meta']['cache'],
    false
);
$entityManager = \Doctrine\ORM\EntityManager::create($doctrine['connection'], $config);
return ConsoleRunner::createHelperSet($entityManager);
