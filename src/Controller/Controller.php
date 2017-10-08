<?php

namespace Oacc\Controller;

use Doctrine\ORM\EntityManager;
use Oacc\Authentication\Authentication;
use Slim\Router;
use Slim\Views\Twig;

/**
 * Class Controller
 * @package Oacc\Controller
 */
class Controller
{
    /**
     * @var Twig $view
     */
    protected $view;

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
    public function __construct($container)
    {
        $this->view = $container->view;
        $this->auth = $container->auth;
        $this->router = $container->router;
    }
}