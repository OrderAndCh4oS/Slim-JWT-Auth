<?php

namespace Oacc\Controller;

use Oacc\Authentication\Exceptions\AuthenticationException;
use Oacc\Authentication\Jwt;
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
    public function loginAction(Request $request, Response $response, $args = [])
    {
        $data = $request->getParsedBody();
        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
        try {
            $user = $this->container->auth->authenticate($credentials);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors());
        } catch (AuthenticationException $e) {
            return JsonEncoder::setErrorJson($response, [$e->getMessage()]);
        }

        return JsonEncoder::setSuccessJson(
            $response,
            'Logged in',
            [
                'token' => Jwt::create($user->getUsername(), $user->getRoles()),
            ]
        );
    }

    public function registerAction(Request $request, Response $response, $args = [])
    {
        try {
            $this->container->auth->register($request);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors());
        }

        return JsonEncoder::setSuccessJson(
            $response,
            'Registered successfully'
        );
    }

    public function logoutAction(Request $request, Response $response, $args = [])
    {
        $this->container->auth->logout($request);

        return JsonEncoder::setSuccessJson(
            $response,
            'Logged out'
        );
    }
}
