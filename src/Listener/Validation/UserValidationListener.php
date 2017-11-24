<?php

namespace Oacc\Listener\Validation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oacc\Entity\User;
use Oacc\Exceptions\ValidationException;

/**
 * Class UserValidationListener
 * @package Oacc\Validation
 */
class UserValidationListener extends ValidationListener
{
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
        parent::__construct($entityManager);
        $this->confirmPassword = $confirmPassword;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->validation($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
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
        $this->checkUsername($user->getUsername(), $user->getId());
        $this->checkEmail($user->getEmailAddress(), $user->getId());
        $this->checkPassword($user->getPlainPassword());
        $this->checkErrors();
    }

    /**
     * @param string $username
     * @param int $id
     */
    private function checkUsername($username, $id)
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
            case !$this->fieldIsAvailable(compact('username'), 'Oacc\Entity\User', $id):
                $this->error->addError('username', 'Username is not available');
                break;
        }
    }

    /**
     * @param string $email
     * @param int $id
     */
    private function checkEmail($email, $id)
    {
        switch (true) {
            case empty($email):
                $this->error->addError('email', 'Please enter an email address');
                break;
            case !filter_var($email, FILTER_VALIDATE_EMAIL):
                $this->error->addError('email', 'Please enter a valid email address');
                break;
            case !$this->fieldIsAvailable(['emailAddress' => $email], 'Oacc\Entity\User', $id):
                $this->error->addError('email', 'An account has already been registered for this address');
                break;
        }
    }

    /**
     * @param string $password
     */
    private function checkPassword($password)
    {
        if (empty($password)) {
            $this->error->addError('password', 'Please enter a password');
        } elseif ($password != $this->confirmPassword) {
            $this->error->addError('password_confirm', 'Passwords do not match');
        }
    }
}
