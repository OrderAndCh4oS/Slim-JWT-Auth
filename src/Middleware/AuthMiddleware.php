<?php

namespace Oacc\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!isset($this->session->username) && !isset($this->session->roles)) {
            return $response->withRedirect($this->router->pathFor('register'));
        }
        if (!in_array('ROLE_USER', $this->session->roles)) {
            return $response->withRedirect($this->router->pathFor('register'));
        }
        if (isset($this->session->username) && isset($this->session->roles)) {
            $this->view->getEnvironment()->addGlobal('user', $this->session->get('username'));
            $this->view->getEnvironment()->addGlobal('roles', $this->session->get('roles'));
        }
        $response = $next($request, $response);

        return $response;
    }
}