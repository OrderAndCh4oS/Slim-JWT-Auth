<?php

namespace Oacc\Middleware;

use RKA\Session;
use Slim\Container;
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
    public function __construct($container)
    {
        $this->view = $container->view;
        $this->session = $container->session;
    }
}