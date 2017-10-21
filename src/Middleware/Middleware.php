<?php

namespace Oacc\Middleware;

use RKA\Session;
use Slim\Container;
use Slim\Router;
use Slim\Views\Twig;

/**
 * Class Middleware
 * @package Oacc\Middleware
 */
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

    /**
     * Middleware constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->view = $container->view;
        $this->session = $container->session;
        $this->router = $container->router;
    }
}
