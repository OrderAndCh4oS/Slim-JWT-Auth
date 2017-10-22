<?php

namespace Oacc\Controller;

use Oacc\Authentication\Authentication;
use Slim\Container;
use Slim\Http\Response;
use Slim\Router;

/**
 * Class Controller
 * @package Oacc\Controller
 */
class Controller
{

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
    public function __construct(Container $container)
    {
        $this->auth = $container->auth;
        $this->router = $container->router;
    }

    /**
     * @param Response $response
     * @param $error_messages
     *
     * @param int $status_code
     * @return Response
     */
    protected function setErrorJson(Response $response, $error_messages, $status_code = 400)
    {
        $data = [
            'status' => 'error',
            'errors' => $error_messages,
        ];

        return $response->withJson($data, $status_code);
    }

    /**
     * @param Response $response
     * @param array $messages
     * @param array $data
     * @param int $status_code
     * @return Response
     */
    protected function setSuccessJson(Response $response, $messages = null, $data = null, $status_code = 200)
    {
        $jsonData = ['status' => 'success'];
        if ($data) {
            $jsonData['data'] = $data;
        }
        if ($messages) {
            $jsonData['messages'] = $messages;
        }

        return $response->withJson($jsonData, $status_code);
    }
}
