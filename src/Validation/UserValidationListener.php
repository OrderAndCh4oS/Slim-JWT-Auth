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
    private $entityManager;

    /**
     * @var string $confirmPassword
     */
    private $confirmPassword;

    /**
     * UserValidationListener constructor.
     * @param string $confirmPassword
     * @param EntityManager $entityManager
     */
    public function __construct($confirmPassword, EntityManager $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
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
            $this->error->addError('username', 'Please enter a username');
        } elseif (strlen($user->getUsername()) > 80) {
            $this->error->addError('username', 'Username is too long');
        } elseif (preg_match('/[^A-Za-z0-9_-]/', $user->getUsername())) {
            $this->error->addError(
                'username',
                'Username can only contain letters, numbers, underscores and hyphens'
            );
        } elseif (!$this->fieldIsAvailable(['username' => $user->getUsername()], 'Oacc\Entity\User')) {
            $this->error->addError('username', 'Username is not available');
        }
    }

    private function fieldIsAvailable($criteria, $entityName)
    {
        $entityRepository = $this->entityManager->getRepository($entityName);
        $entity = $entityRepository->findOneBy($criteria);

        return !$entity;
    }

    /**
     * @param User $user
     */
    private function checkEmail(User $user)
    {
        if (empty($user->getEmailAddress())) {
            $this->error->addError('email', 'Please enter an email address');
        } elseif (!filter_var($user->getEmailAddress(), FILTER_VALIDATE_EMAIL)) {
            $this->error->addError('email', 'Please enter a valid email address');
        } elseif (!$this->fieldIsAvailable(['emailAddress' => $user->getEmailAddress()], 'Oacc\Entity\User')) {
            $this->error->addError('email', 'An account has already been registered for this address');
        }
    }

    /**
     * @param User $user
     */
    private function checkPassword(User $user)
    {
        if (empty($user->getPlainPassword())) {
            $this->error->addError('password', 'Please enter a password');
        } elseif ($user->getPlainPassword() != $this->confirmPassword) {
            $this->error->addError('password_confirm', 'Passwords do not match');
        }
    }
}
