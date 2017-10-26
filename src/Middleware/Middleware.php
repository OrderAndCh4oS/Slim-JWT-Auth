<?php

namespace Oacc\Middleware;

use Doctrine\ORM\EntityManager;
use Slim\Container;
use Slim\Router;

/**
 * Class Middleware
 * @package Oacc\Middleware
 */
class Middleware
{
    /**
     * Middleware constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
