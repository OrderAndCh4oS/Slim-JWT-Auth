<?php

namespace Oacc\Controller;

use Oacc\Service\AuthenticationService;
use Oacc\Utility\CheckCredentials;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthController
 * @package Oacc\Controller
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function loginAction(Request $request, Response $response)
    {
        return (new AuthenticationService(new CheckCredentials($this->container)))->authenticate($request, $response);
    }
}
