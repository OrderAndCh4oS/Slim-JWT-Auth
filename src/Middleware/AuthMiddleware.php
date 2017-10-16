<?php

namespace Oacc\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware extends Middleware
{
    /**
     * @var array
     */
    private $allowedRoles;

    public function __construct($container, $allowedRoles = ['ROLE_USER'])
    {
        parent::__construct($container);
        $this->allowedRoles = $allowedRoles;
    }

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

    private function hasAuthData()
    {
        return isset($this->session->user) && isset($this->session->roles);
    }

    private function hasAllowedRoles()
    {
        return !empty(array_intersect($this->allowedRoles, $this->session->roles));
    }
}