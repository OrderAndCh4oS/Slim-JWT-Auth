<?php

namespace Oacc\Middleware;

use Slim\Container;
use Slim\Router;

/**
 * Class Middleware
 * @package Oacc\Middleware
 */
class Middleware
{
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
        $this->router = $container->router;
    }
}
