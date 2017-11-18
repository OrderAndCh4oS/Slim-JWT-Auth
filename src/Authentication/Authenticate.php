<?php

namespace Oacc\Authentication;

use Doctrine\ORM\EntityRepository;
use Oacc\Authentication\Exceptions\AuthenticationException;
use Oacc\Entity\User;
use Oacc\Error\Error;
use Oacc\Service\JsonEncoder;
use Oacc\Validation\Exceptions\ValidationException;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Authentication
 * @package Oacc\Authentication
 */
class Authenticate
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Authentication constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function authenticate(Request $request, Response $response)
    {
        $credentials = $request->getParsedBody();
        $this->checkCredentialsAreNotEmpty($credentials);
        $user = $this->checkCredentials($credentials);
        $token = Jwt::create($user->getUsername(), $user->getRoles());

        return JsonEncoder::setSuccessJson($response, 'Logged in', compact('token'));
    }

    /**
     * @param $credentials
     * @throws ValidationException
     */
    private function checkCredentialsAreNotEmpty($credentials)
    {
        $errors = new Error();
        if (empty($credentials['username'])) {
            $errors->addError('username', 'Missing username');
        }
        if (empty($credentials['password'])) {
            $errors->addError('password', 'Missing password');
        }
        if ($errors->hasErrors()) {
            throw new ValidationException($errors, "Login Failed");
        }
    }

    /**
     * @param $credentials
     * @return User
     * @throws AuthenticationException
     */
    private function checkCredentials($credentials): User
    {
        /** @var EntityRepository $userRepository */
        $userRepository = $this->container->em->getRepository('\Oacc\Entity\User');
        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => $credentials['username']]);
        if (!$user || !password_verify($credentials['password'], $user->getPassword())) {
            (new Error())->addError('auth', 'Please enter your login details');
            throw new AuthenticationException("Invalid credentials, login failed");
        }

        return $user;
    }
}
