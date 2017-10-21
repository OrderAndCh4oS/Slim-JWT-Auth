<?php
// DIC configuration
use Oacc\Message\Error;
use Oacc\Message\Message;
use RKA\Session;
use Slim\Container;
use Slim\Csrf\Guard;

$container = $app->getContainer();
$container['logger'] = function (Container $container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};
// Register component on container
$container['view'] = function (Container $container) {
    $settings = $container->get('settings')['view'];
    if ($settings['debug'] === true) {
        $viewOptions = [
            'cache' => false,
            'debug' => true,
        ];
    } else {
        $viewOptions = [
            'cache' => __DIR__.'/../cache',
        ];
    }
    $view = new \Slim\Views\Twig($settings["template_path"], $viewOptions);
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new Slim\Views\TwigExtension($container->router, $container->request->getUri()));

    return $view;
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
    $em = \Doctrine\ORM\EntityManager::create($settings['connection'], $config);

    return $em;
};
$container['csrf'] = function () {
    return new Guard;
};
$container['session'] = function () {
    return new Session;
};
$container['message'] = function () {
    return new Message();
};
$container['error'] = function () {
    return new Error();
};
$container['auth'] = function (Container $container) {
    return new Oacc\Authentication\Authentication($container);
};
$container['Oacc\Controller\AuthController'] = function (Container $container) {
    return new Oacc\Controller\AuthController($container);
};
$container['Oacc\Controller\AdminController'] = function (Container $container) {
    return new Oacc\Controller\AdminController($container);
};
$container['Oacc\Controller\PageController'] = function (Container $container) {
    return new Oacc\Controller\PageController($container);
};
$container['Oacc\Middleware\AuthMiddleware'] = function (Container $container) {
    return new Oacc\Middleware\AuthMiddleware($container);
};
