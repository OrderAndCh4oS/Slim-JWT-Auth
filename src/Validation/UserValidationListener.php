<?php

namespace Oacc\Validation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oacc\Entity\User;
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
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->validation($entity);
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
        $this->checkUsername($user->getUsername());
        $this->checkEmail($user->getEmailAddress());
        $this->checkPassword($user->getPlainPassword());
        $this->checkErrors();
    }

    private function checkUsername($username)
    {
        switch (true) {
            case empty($username):
                $this->error->addError('username', 'Please enter a username');
                break;
            case strlen($username) > 80:
                $this->error->addError('username', 'Username is too long');
                break;
            case preg_match('/[^A-Za-z0-9_-]/', $username):
                $this->error->addError(
                    'username',
                    'Username can only contain letters, numbers, underscores and hyphens'
                );
                break;
            case !$this->fieldIsAvailable(['username' => $username], 'Oacc\Entity\User'):
                $this->error->addError('username', 'Username is not available');
                break;
        }
    }

    private function fieldIsAvailable($criteria, $entityName)
    {
        $entityRepository = $this->entityManager->getRepository($entityName);
        $entity = $entityRepository->findOneBy($criteria);

        return !$entity;
    }

    private function checkEmail($email)
    {
        switch (true) {
            case empty($email):
                $this->error->addError('email', 'Please enter an email address');
                break;
            case !filter_var($email, FILTER_VALIDATE_EMAIL):
                $this->error->addError('email', 'Please enter a valid email address');
                break;
            case !$this->fieldIsAvailable(['emailAddress' => $email], 'Oacc\Entity\User'):
                $this->error->addError('email', 'An account has already been registered for this address');
                break;
        }
    }

    private function checkPassword($password)
    {
        if (empty($password)) {
            $this->error->addError('password', 'Please enter a password');
        } elseif ($password != $this->confirmPassword) {
            $this->error->addError('password_confirm', 'Passwords do not match');
        }
    }
}
