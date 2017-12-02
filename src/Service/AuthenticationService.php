<?php

namespace Oacc\Service;

use Oacc\Utility\BuildJwt;
use Oacc\Utility\CheckCredentials;
use Oacc\Utility\JsonResponse;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Authentication
 * @package Oacc\Authentication
 */
class AuthenticationService
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var CheckCredentials
     */
    private $checkCredentials;

    /**
     * Authentication constructor.
     * @param CheckCredentials $checkCredentials
     */
    public function __construct(CheckCredentials $checkCredentials)
    {
        $this->checkCredentials = $checkCredentials;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function authenticate(Request $request, Response $response)
    {
        $credentials = $request->getParsedBody();
        if (!$this->checkCredentials->areNotEmpty($credentials) || !$this->checkCredentials->areValid($credentials)) {
            return JsonResponse::setErrorJson($response, $this->checkCredentials->getErrors());
        }
        $token = (new BuildJwt())
            ->addClaims($this->checkCredentials->getData())
            ->sign();

        return JsonResponse::setSuccessJson($response, ['Logged in'], compact('token'));
    }
}
