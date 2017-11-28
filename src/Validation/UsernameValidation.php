<?php

namespace Oacc\Validation;

use Doctrine\ORM\EntityManager;
use Oacc\Entity\User;
use Oacc\Utility\Error;

class UsernameValidation extends FieldValidation
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->user = $user;
    }

    public function validate(Error $error)
    {
        $username = $this->user->getUsername();
        switch (true) {
            case empty($username):
                $error->addError('username', 'Please enter a username');
                break;
            case strlen($username) > 80:
                $error->addError('username', 'Username is too long');
                break;
            case preg_match('/[^A-Za-z0-9_-]/', $username):
                $error->addError(
                    'username',
                    'Username can only contain letters, numbers, underscores and hyphens'
                );
                break;
            case !(new UniqueFieldValidation($this->entityManager))
                ->isUnique(compact('username'), 'Oacc\Entity\User', $this->user->getId()):
                $error->addError('username', 'Username is not available');
                break;
        }

        return $error;
    }
}
