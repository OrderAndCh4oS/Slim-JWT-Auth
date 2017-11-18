<?php

namespace Oacc\Controller;

use Oacc\Authentication\Authenticate;
use Oacc\Authentication\Exceptions\AuthenticationException;
use Oacc\Authentication\Register;
use Oacc\Entity\User;
use Oacc\Service\JsonEncoder;
use Oacc\Validation\Exceptions\ValidationException;
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
        try {
            $response = (new Authenticate($this->container))->authenticate($request, $response);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors());
        } catch (AuthenticationException $e) {
            return JsonEncoder::setErrorJson($response, [$e->getMessage()]);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function registerAction(Request $request, Response $response)
    {
        try {
            /** @var User $user */
            $response = (new Register($this->container))->register($request, $response);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors());
        }

        return $response;
    }
}
