<?php

namespace Oacc\Middleware;

use Slim\Container;

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
