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
        $app->post('/register', AuthController::class.':registerAction')->setName('register');
        $app->post('/login', AuthController::class.':loginAction')->setName('login');
        $app->group(
            '/admin',
            function () use ($app) {
                $app->get('', UserController::class.':indexAction')->setName('dashboard');
            }
        )->add(new AuthMiddleware($container));
    }
}
