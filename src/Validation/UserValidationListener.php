<?php

namespace Oacc\Validation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oacc\Entity\User;
use Oacc\Session\Error;
use Oacc\Validation\Exceptions\ValidationException;

/**
 * Class UserValidationListener
 * @package Oacc\Validation
 */
class UserValidationListener extends ValidationListener
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string $confirmPassword
     */
    private $confirmPassword;

    /**
     * UserValidationListener constructor.
     * @param string $confirmPassword
     * @param EntityManager $em
     * @param Error $error
     */
    public function __construct($confirmPassword, EntityManager $em, Error $error)
    {
        parent::__construct($error);
        $this->em = $em;
        $this->confirmPassword = $confirmPassword;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->validation($entity);
        $this->checkErrors();
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->validation($entity);
        $this->checkErrors();
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    /**
     * @param User $user
     * @throws ValidationException
     */
    public function validation(User $user)
    {
        $this->checkUsername($user);
        $this->checkEmail($user);
        $this->checkPassword($user);
    }

    /**
     * @param User $user
     */
    private function checkUsername(User $user)
    {
        if (empty($user->getUsername())) {
            $this->error->setError('username', 'Please enter a username');
        } elseif (strlen($user->getUsername()) > 80) {
            $this->error->setError('username', 'Username is too long');
        } elseif (preg_match('/[^A-Za-z0-9_-]/', $user->getUsername())) {
            $this->error->setError(
                'username',
                'Username can only contain letters, numbers, underscores and hyphens'
            );
        } elseif (!$this->fieldIsAvailable(['username' => $user->getUsername()], 'Oacc\Entity\User')) {
            $this->error->setError('username', 'Username is not available');
        }
    }

    private function fieldIsAvailable($criteria, $entityName)
    {
        $entityRepository = $this->em->getRepository($entityName);
        $entity = $entityRepository->findOneBy($criteria);

        return !$entity;
    }

    /**
     * @param User $user
     */
    private function checkEmail(User $user)
    {
        if (empty($user->getEmailAddress())) {
            $this->error->setError('email', 'Please enter an email address');
        } elseif (!filter_var($user->getEmailAddress(), FILTER_VALIDATE_EMAIL)) {
            $this->error->setError('email', 'Please enter a valid email address');
        } elseif (!$this->fieldIsAvailable(['emailAddress' => $user->getEmailAddress()], 'Oacc\Entity\User')) {
            $this->error->setError('email', 'An account has already been registered');
        }
    }

    /**
     * @param User $user
     */
    private function checkPassword(User $user)
    {
        if (empty($user->getPlainPassword())) {
            $this->error->setError('password', 'Please enter a password');
        } elseif ($user->getPlainPassword() != $this->confirmPassword) {
            $this->error->setError('password_confirm', 'Passwords do not match');
        }
    }
}
