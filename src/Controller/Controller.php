<?php

namespace Oacc\Controller;

use Oacc\Authentication\Authentication;
use Slim\Container;
use Slim\Router;

/**
 * Class Controller
 * @package Oacc\Controller
 */
class Controller
{

    /**
     * @var Authentication $auth
     */
    protected $auth;

    /**
     * @var Router $router
     */
    protected $router;

    /**
     * Controller constructor.
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->auth = $container->auth;
        $this->router = $container->router;
    }
}
