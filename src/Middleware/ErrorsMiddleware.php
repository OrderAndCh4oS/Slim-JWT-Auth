<?php

namespace Oacc\Middleware;

class ErrorsMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(isset($this->session->errors)) {
            $this->view->getEnvironment()->addGlobal('errors', $this->session->get('errors'));
            unset($this->session->errors);
        }
        $response = $next($request, $response);
        return $response;
    }
}