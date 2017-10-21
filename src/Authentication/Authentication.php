<?php

namespace Oacc\Authentication;

use Doctrine\ORM\EntityManager;
use Oacc\Authentication\Exceptions\AuthenticationException;
use Oacc\Entity\User;
use Oacc\Entity\UserInterface;
use Oacc\Message\Error;
use Oacc\Security\HashPasswordListener;
use Oacc\Security\UserPasswordEncoder;
use Oacc\Validation\Exceptions\ValidationException;
use Oacc\Validation\UserValidationListener;
use RKA\Session;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class Authentication
 * @package Oacc\Authentication
 */
class Authentication
{
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var Session $session
     */
    protected $session;

    /**
     * @var Error
     */
    protected $error;

    /**
     * Authentication constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->em = $container->em;
        $this->error = $container->error;
        $this->session = $container->session;
    }

    /**
     * @param $credentials
     * @return User
     * @throws AuthenticationException
     */
    public function authenticate($credentials): User
    {
        if (empty($credentials['username'])) {
            $this->error->setError('username', 'Missing username');
        }
        if (empty($credentials['password'])) {
            $this->error->setError('password', 'Missing password');
        }
        if ($this->error->hasErrors()) {
            throw new AuthenticationException("Please enter your login details");
        }
        /** @var EntityManager $em */
        $userRepository = $this->em->getRepository('\Oacc\Entity\User');
        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => $credentials['username']]);
        if (!$user || !password_verify($credentials['password'], $user->getPassword())) {
            throw new AuthenticationException("Invalid login details");
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     */
    public function login(UserInterface $user)
    {
        Session::regenerate();
        $this->session->user = $user->getUsername();
        $this->session->roles = $user->getRoles();
    }

    /**
     *
     */
    public function logout()
    {
        Session::destroy();
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function register(Request $request)
    {
        /** @var EntityManager $em */
        $evm = $this->em->getEventManager();
        $evm->addEventListener(
            ['prePersist', 'preUpdate'],
            new UserValidationListener($request->getParam('password-confirm'), $this->em, $this->error)
        );
        $evm->addEventListener(['prePersist', 'preUpdate'], new HashPasswordListener(new UserPasswordEncoder()));
        $user = new User();
        $user->setUsername($request->getParam('username'));
        $user->setEmailAddress($request->getParam('email'));
        $user->setPlainPassword($request->getParam('password'));
        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (ValidationException $e) {
            return false;
        }

        return true;
    }
}
