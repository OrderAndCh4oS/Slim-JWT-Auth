<?php

namespace Oacc\Utility;

use Doctrine\ORM\EntityRepository;
use Oacc\Entity\User;
use Slim\Container;

class CheckCredentials
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
     * @param $credentials
     * @return bool
     */
    public function areNotEmpty($credentials)
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
    public function areValid($credentials)
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

    public function getErrors()
    {
        return $this->errors->getErrors();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'username' => $this->user->getUsername(),
            'roles' => $this->user->getRoles(),
        ];
    }

}
