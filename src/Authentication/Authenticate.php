<?php

namespace Oacc\Authentication;

use Doctrine\ORM\EntityRepository;
use Oacc\Authentication\Exceptions\AuthenticationException;
use Oacc\Entity\User;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;
use Slim\Container;

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
     * @param $credentials
     * @return User
     * @throws AuthenticationException
     * @throws ValidationException
     */
    public function authenticate($credentials): User
    {
        $this->checkCredentialsAreNotEmpty($credentials);
        $user = $this->checkCredentials($credentials);

        return $user;
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
