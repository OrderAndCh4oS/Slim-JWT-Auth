<?php

namespace Oacc\Validation\User;

use Doctrine\ORM\EntityManager;
use Oacc\Entity\User;
use Oacc\Utility\Error;
use Oacc\Validation\Field\Email;
use Oacc\Validation\Field\FieldValidation;
use Oacc\Validation\Field\NotEmpty;
use Oacc\Validation\Field\Unique;
use Oacc\Validation\Field\ValidateFields;

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
        $email = $this->user->getEmail();
        $error->setName('email');
        $validate = new ValidateFields($error);
        $validate->addCheck(new NotEmpty($email))
            ->addCheck(new Email($email))
            ->addCheck(
                new Unique(
                    'Oacc\Entity\User',
                    compact('email'),
                    $this->user->getId(),
                    $this->entityManager
                )
            )->validate();
    }
}
