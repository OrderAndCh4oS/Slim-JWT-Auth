<?php

namespace Oacc\Authentication;

use Doctrine\ORM\EntityManager;
use Oacc\Entity\User;
use Oacc\Error\Error;
use Oacc\Security\HashPasswordListener;
use Oacc\Security\UserPasswordEncoder;
use Oacc\Validation\Exceptions\ValidationException;
use Oacc\Validation\UserValidationListener;
use RKA\Session;
use Slim\Container;
use Slim\Http\Request;

class Authentication
{
    protected $container;
    /**
     * @var Session $session
     */
    protected $session;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->session = $container->session;
    }

    /**
     * @param $credentials
     * @return User|false
     */
    public function authenticate($credentials)
    {
        if (!array_key_exists('username', $credentials) || !array_key_exists('password', $credentials)) {
            return false;
        }
        /** @var EntityManager $em */
        $em = $this->container->em;
        $userRepository = $em->getRepository('\Oacc\Entity\User');
        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => $credentials['username']]);
        if (!$user) {
            return false;
        }
        if (!password_verify($credentials['password'], $user->getPassword())) {
            return false;
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function login(User $user)
    {
        Session::regenerate();
        $this->session->user = $user->getUsername();
        $this->session->roles = $user->getRoles();
    }

    public function logout()
    {
        Session::destroy();
    }

    /**
     * @param Request $request
     * @return bool|User
     */
    public function register(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->container->em;
        $evm = $em->getEventManager();
        $evm->addEventListener(['prePersist', 'preUpdate'], new UserValidationListener($em, new Error()));
        $evm->addEventListener(['prePersist', 'preUpdate'], new HashPasswordListener(new UserPasswordEncoder()));
        $user = new User();
        $user->setUsername($request->getParam('username'));
        $user->setEmailAddress($request->getParam('email'));
        $user->setPlainPassword($request->getParam('password'));
        try {
            $em->persist($user);
            $em->flush();
        } catch (ValidationException $e) {
            return false;
        }

        return $user;
    }

    public function hasRole($role = "ROLE_USER")
    {
        return in_array($role, $this->getUser()->getRoles());
    }

    /**
     * @return User|false
     */
    public function getUser()
    {
        return false;
    }
}
