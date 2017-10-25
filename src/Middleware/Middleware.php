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
     * @var Router
     */
    protected $router;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Middleware constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->router = $container->router;
        $this->em = $container->em;
    }
}
