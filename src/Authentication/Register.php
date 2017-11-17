<?php

namespace Oacc\Authentication;

use Doctrine\Common\EventManager;
use Oacc\Entity\User;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;
use Oacc\Validation\UserValidationListener;
use Slim\Container;

class Register
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
     * @param $data
     * @return User
     * @throws ValidationException
     */
    public function register($data)
    {
        $this->checkDataIsNotEmpty($data);
        $this->setListeners($data);
        $user = $this->createUser($data);

        return $user;
    }

    /**
     * @param $data
     * @throws ValidationException
     */
    private function checkDataIsNotEmpty($data)
    {
        if (empty($data)) {
            throw new ValidationException(
                new Error(['No valid data. Post username, email, password and password_confirm as json'])
            );
        }
    }

    /**
     * @param $data
     */
    private function setListeners($data)
    {
        /** @var EventManager $eventManager */
        $eventManager = $this->container->em->getEventManager();
        $eventManager->addEventListener(
            ['prePersist', 'preUpdate'],
            new UserValidationListener($data['password_confirm'], $this->container->em)
        );
        $eventManager->addEventListener(
            ['prePersist', 'preUpdate'],
            new HashPasswordListener(new UserPasswordEncoder())
        );
    }

    /**
     * @param $data
     * @return User
     */
    private function createUser($data): User
    {
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmailAddress($data['email']);
        $user->setPlainPassword($data['password']);
        $this->container->em->persist($user);
        $this->container->em->flush();

        return $user;
    }
}
