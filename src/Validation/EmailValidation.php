<?php

namespace Oacc\Validation;

use Doctrine\ORM\EntityManager;
use Oacc\Entity\User;
use Oacc\Utility\Error;
use Oacc\Validation\Field\FieldValidation;
use Oacc\Validation\Field\UniqueFieldValidation;

class EmailValidation extends FieldValidation
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
        $email = $this->user->getEmailAddress();
        switch (true) {
            case empty($email):
                $error->addError('email', 'Please enter an email address');
                break;
            case !filter_var($email, FILTER_VALIDATE_EMAIL):
                $error->addError('email', 'Please enter a valid email address');
                break;
            case !(new UniqueFieldValidation($this->entityManager))
                ->isUnique(['emailAddress' => $email], 'Oacc\Entity\User', $this->user->getId()):
                $error->addError('email', 'An account has already been registered for this address');
                break;
        }
    }
}
