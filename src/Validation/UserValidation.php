<?php

namespace Oacc\Validation;

use Doctrine\ORM\EntityManager;
use Oacc\Entity\User;

/**
 * Class UserValidation
 * @package Oacc\Validation
 */
class UserValidation extends Validation
{
    /**
     * @var string $confirmPassword
     */
    private $confirmPassword;

    public function __construct($confirmPassword, EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->confirmPassword = $confirmPassword;
    }

    /**
     * @param $entity
     */
    public function validate($entity)
    {
        if (!$entity instanceof User) {
            return;
        }
        $user = $entity;
        $this->checkUsername($user->getUsername(), $user->getId());
        $this->checkEmail($user->getEmailAddress(), $user->getId());
        $this->checkPassword($user->getPlainPassword());
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
        switch (true) {
            case empty($password):
                $this->error->addError('password', 'Please enter a password');
                break;
            case strlen($password) < 8:
                $this->error->addError('password', 'Password must contain a minimum of 8 characters');
                break;
            case $password != $this->confirmPassword:
                $this->error->addError('password_confirm', 'Passwords do not match');
                break;
        }
    }
}
