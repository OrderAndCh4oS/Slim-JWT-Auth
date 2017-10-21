<?php

namespace Oacc\Middleware;

class MessageMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($this->session->messageBag)) {
            foreach ($this->session->messageBag as $type => $messages) {
                $this->view->getEnvironment()->addGlobal($type, $messages);
            }
            unset($this->session->messageBag);
        }
        $response = $next($request, $response);

        return $response;
    }
}
