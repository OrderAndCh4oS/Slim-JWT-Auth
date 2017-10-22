<?php

namespace Oacc\Middleware;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthMiddleware
 * @package Oacc\Middleware
 */
class AuthMiddleware extends Middleware
{
    /**
     * @var array
     */
    private $allowedRoles;

    /**
     * AuthMiddleware constructor.
     * @param Container $container
     * @param array $allowedRoles
     */
    public function __construct(Container $container, $allowedRoles = ['ROLE_USER'])
    {
        parent::__construct($container);
        $this->allowedRoles = $allowedRoles;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return Response|static
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->hasAuthData() || !$this->hasAllowedRoles()) {
            return $response->withRedirect($this->router->pathFor('register'));
        }
        $this->view->getEnvironment()->addGlobal('user', $this->session->get('user'));
        $this->view->getEnvironment()->addGlobal('roles', $this->session->get('roles'));
        $response = $next($request, $response);

        return $response;
    }

    /**
     * @return bool
     */
    private function hasAuthData()
    {
        return isset($this->session->user) && isset($this->session->roles);
    }

    /**
     * @return bool
     */
    private function hasAllowedRoles()
    {
        return !empty(array_intersect($this->allowedRoles, $this->session->roles));
    }
}
