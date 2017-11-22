<?php

namespace Oacc\Authentication;

use Doctrine\ORM\EntityRepository;
use Oacc\Utility\Jwt;
use Oacc\Utility\PasswordEncoder;
use Oacc\Entity\User;
use Oacc\Utility\Error;
use Oacc\Utility\JsonEncoder;
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
     * @var Error
     */
    private $errors;

    /**
     * @var User
     */
    private $user;

    /**
     * Authentication constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->errors = new Error();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function authenticate(Request $request, Response $response)
    {
        $credentials = $request->getParsedBody();
        if (!$this->checkCredentialsAreNotEmpty($credentials) || !$this->checkCredentials($credentials)) {
            return JsonEncoder::setErrorJson($response, $this->errors->getErrors());
        }
        $token = Jwt::create($this->user->getUsername(), $this->user->getRoles());

        return JsonEncoder::setSuccessJson($response, ['Logged in'], compact('token'));
    }

    /**
     * @param $credentials
     * @return bool
     */
    private function checkCredentialsAreNotEmpty($credentials)
    {
        if (empty($credentials['username'])) {
            $this->errors->addError('username', 'Missing username');
        }
        if (empty($credentials['password'])) {
            $this->errors->addError('password', 'Missing password');
        }

        return !$this->errors->hasErrors();
    }

    /**
     * @param $credentials
     * @return bool
     */
    private function checkCredentials($credentials)
    {
        /** @var EntityRepository $userRepository */
        $userRepository = $this->container->em->getRepository('\Oacc\Entity\User');
        /** @var User $user */
        $this->user = $userRepository->findOneBy(['username' => $credentials['username']]);
        if (!$this->user || !PasswordEncoder::verifyPassword($credentials['password'], $this->user->getPassword())) {
            $this->errors->addError('auth', 'Invalid credentials, login failed');
        }

        return !$this->errors->hasErrors();
    }
}
