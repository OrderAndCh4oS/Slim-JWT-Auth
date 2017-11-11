<?php
// DIC configuration
use Slim\Container;

$container = $app->getContainer();
$container['logger'] = function (Container $container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};
$container['em'] = function (Container $container) {
    $settings = $container->get('settings')['doctrine'];
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $settings['meta']['entity_path'],
        $settings['meta']['auto_generate_proxies'],
        $settings['meta']['proxy_dir'],
        $settings['meta']['cache'],
        false
    );
    $entityManager = \Doctrine\ORM\EntityManager::create($settings['connection'], $config);

    return $entityManager;
};
$container['fractal'] = function () {
    return new \League\Fractal\Manager();
};
$container['auth'] = function (Container $container) {
    return new Oacc\Authentication\Authentication($container);
};
$container['Oacc\Controller\AuthController'] = function (Container $container) {
    return new Oacc\Controller\AuthController($container);
};
$container['Oacc\Controller\UserController'] = function (Container $container) {
    return new Oacc\Controller\UserController($container);
};
$container['Oacc\Middleware\AuthMiddleware'] = function (Container $container) {
    return new Oacc\Middleware\AuthMiddleware($container);
};
