<?php

namespace Oacc\Controller;

use Doctrine\ORM\EntityManager;
use Oacc\Service\AuthenticationService;
use Slim\Container;
use Slim\Router;

/**
 * Class Controller
 * @package Oacc\Controller
 */
class Controller
{

    /**
     * @var Container $container
     */
    protected $container;

    /**
     * Controller constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
