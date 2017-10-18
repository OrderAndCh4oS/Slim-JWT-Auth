<?php

namespace Oacc\Controller;

use Oacc\Authentication\Authentication;
use Oacc\Error\Error;
use RKA\Session;
use Slim\Container;
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
     * @var Session $session
     */
    protected $session;

    /**
     * @var Error
     */
    protected $error;

    /**
     * Controller constructor.
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->view = $container->view;
        $this->auth = $container->auth;
        $this->router = $container->router;
        $this->session = $container->session;
        $this->error = $container->error;
    }
}
