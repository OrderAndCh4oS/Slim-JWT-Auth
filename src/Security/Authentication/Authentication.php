<?php

namespace Oacc\Security\Authentication;

use Doctrine\ORM\EntityManager;
use Oacc\Security\Authentication\Exceptions\AuthenticationException;
use Oacc\Entity\User;
use Oacc\Error\Error;
use Oacc\Security\HashPasswordListener;
use Oacc\Security\UserPasswordEncoder;
use Oacc\Validation\Exceptions\ValidationException;
use Oacc\Validation\UserValidationListener;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class Authentication
 * @package Oacc\Authentication
 */
class Authentication
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
        /** @var EntityManager $em */
        $userRepository = $this->container->em->getRepository('\Oacc\Entity\User');
        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => $credentials['username']]);
        if (!$user || !password_verify($credentials['password'], $user->getPassword())) {
            $errors->addError('auth', 'Please enter your login details');
            throw new AuthenticationException("Invalid credentials, login failed");
        }

        return $user;
    }

    /**
     * @param Request $request
     */
    public function logout(Request $request)
    {
        // ToDo: add token to some kind of blacklist cache
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        // ToDo: Decide where to put this, doesn't feel as though it belongs here
        $data = $request->getParsedBody();
        /** @var EntityManager $em */
        $evm = $this->container->em->getEventManager();
        $evm->addEventListener(
            ['prePersist', 'preUpdate'],
            new UserValidationListener($data['password_confirm'], $this->container->em)
        );
        $evm->addEventListener(['prePersist', 'preUpdate'], new HashPasswordListener(new UserPasswordEncoder()));
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmailAddress($data['email']);
        $user->setPlainPassword($data['password']);
        $this->container->em->persist($user);
        $this->container->em->flush();
    }
}