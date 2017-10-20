<?php

namespace Oacc\Middleware;

class MessageMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($this->session->messageBag)) {
            array_map(
                function ($type, $messages) {
                    $this->view->getEnvironment()->addGlobal($type, $messages);
                },
                array_keys($this->session->messageBag),
                $this->session->messageBag
            );
            unset($this->session->messageBag);
        }
        $response = $next($request, $response);

        return $response;
    }
}
