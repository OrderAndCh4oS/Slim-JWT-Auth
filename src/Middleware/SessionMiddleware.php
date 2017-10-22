<?php

namespace Oacc\Middleware;

/**
 * Class MessageMiddleware
 * @package Oacc\Middleware
 */
class SessionMiddleware extends Middleware
{
    /**
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function __invoke($request, $response, $next)
    {
        if (isset($this->session->dataBag)) {
            foreach ($this->session->dataBag as $type => $data) {
                $this->view->getEnvironment()->addGlobal($type, $data);
            }
            unset($this->session->dataBag);
        }
        $response = $next($request, $response);

        return $response;
    }
}
