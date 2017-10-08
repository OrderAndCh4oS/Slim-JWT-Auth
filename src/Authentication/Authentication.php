<?php

namespace Oacc\Authentication;

use Oacc\Entity\User;
use Doctrine\ORM\EntityManager;
use Oacc\Validation\Exceptions\ValidationException;
use Slim\Http\Request;

class Authentication
{
    protected $container;
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param $credentials
     * @return User|false
     */
    public function authenticate($credentials) {
        return false;
    }

    /**
     * @param $user
     */
    public function login($user) {

    }

    public function logout() {

    }

    /**
     * @param Request $request
     * @return bool|User
     */
    public function register(Request $request) {
        /** @var EntityManager $em */
        $em = $this->container->em;
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

    public function hasRole($role = "ROLE_USER") {
        return in_array($role, $this->getUser()->getRoles());
    }

    /**
     * @return User|false
     */
    public function getUser() {
        return false;
    }
}