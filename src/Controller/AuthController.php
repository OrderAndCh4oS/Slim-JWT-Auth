<?php

namespace Oacc\Controller;

use Oacc\Entity\User;
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
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function loginAction(Request $request, Response $response)
    {
        $credentials = $request->getParsedBody();
        try {
            /** @var User $user */
            $user = $this->container->auth->authenticate($credentials);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors());
        } catch (AuthenticationException $e) {
            return JsonEncoder::setErrorJson($response, [$e->getMessage()]);
        }
        $token = Jwt::create($user->getUsername(), $user->getRoles());

        return JsonEncoder::setSuccessJson($response, 'Logged in', ['token' => $token]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function registerAction(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();
            /** @var User $user */
            $user = $this->container->auth->register($data);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors());
        }

        return JsonEncoder::setSuccessJson($response, $user->getUsername().' registered successfully');
    }
}
