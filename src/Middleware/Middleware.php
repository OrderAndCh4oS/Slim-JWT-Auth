<?php

namespace Oacc\Middleware;

use RKA\Session;
use Slim\Container;
use Slim\Router;
use Slim\Views\Twig;

class Middleware
{
    /**
     * @var Twig
     */
    protected $view;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Router
     */
    protected $router;

    public function __construct(Container $container)
    {
        $this->view = $container->view;
        $this->session = $container->session;
        $this->router = $container->router;
    }
}
