<?php

namespace Oacc\Controller;

use Slim\Container;

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
