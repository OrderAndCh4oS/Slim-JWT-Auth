<?php

namespace Oacc;

use Oacc\Controller\AuthController;
use Oacc\Controller\UserController;
use Oacc\Middleware\AuthMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;

class Routes
{
    /**
     * @param $app
     * @param $container
     */
    public function __construct(App $app, ContainerInterface $container)
    {
        $app->post('/user', UserController::class.':postAction');
        $app->post('/login', AuthController::class.':loginAction');
        $app->group(
            '',
            function () use ($app) {
                $app->get('/user', UserController::class.':getAction');
                $app->put('/user', UserController::class.':putAction');
            }
        )->add(new AuthMiddleware($container));
    }
}
