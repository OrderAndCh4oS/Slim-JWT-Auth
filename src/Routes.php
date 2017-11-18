<?php

namespace Oacc;

use Oacc\Controller\AuthController;
use Oacc\Controller\UserController;
use Oacc\Middleware\AuthMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Container;

class Routes
{
    /**
     * @param $app
     * @param $container
     */
    public function __construct(App $app, ContainerInterface $container)
    {
        $app->get('/test', UserController::class.':indexAction');
        $app->post('/user', UserController::class.':postAction');
        $app->post('/login', AuthController::class.':loginAction');
        $app->group(
            '',
            function () use ($app) {
                $app->get('/user', UserController::class.':getAction');
            }
        )->add(new AuthMiddleware($container));
    }
}
