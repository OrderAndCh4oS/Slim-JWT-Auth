<?php

namespace Oacc\Validation\User;

use Doctrine\ORM\EntityManager;
use Oacc\Entity\User;
use Oacc\Utility\Error;
use Oacc\Validation\Field\FieldValidation;
use Oacc\Validation\Field\Length;
use Oacc\Validation\Field\NotEmpty;
use Oacc\Validation\Field\Regex;
use Oacc\Validation\Field\Unique;
use Oacc\Validation\Field\ValidateFields;

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

    /**
     * @param Error $error
     */
    public function validate(Error $error)
    {
        $username = $this->user->getUsername();
        $error->setName('username');
        $validate = new ValidateFields($error);
        $validate->addCheck(new NotEmpty($username))
            ->addCheck(new Length($username, 80))
            ->addCheck(new Regex($username, '/[^A-Za-z0-9_-]/'))
            ->addCheck(
                new Unique(
                    'Oacc\Entity\User',
                    compact('username'),
                    $this->user->getId(),
                    $this->entityManager
                )
            )->validate();
    }
}
