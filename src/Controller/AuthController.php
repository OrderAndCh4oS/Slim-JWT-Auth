<?php

namespace Oacc\Controller;

use Oacc\Authentication\CheckCredentials;
use Oacc\Authentication\Authenticate;
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
        return (new Authenticate(new CheckCredentials($this->container)))->authenticate($request, $response);
    }
}
