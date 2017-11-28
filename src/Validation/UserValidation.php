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
        parent::__construct();
        $this->confirmPassword = $confirmPassword;
        $this->entityManager = $entityManager;
    }

    public function validate($entity)
    {
        if (!($entity instanceof User)) {
            return;
        }
        $user = $entity;
        $validation = new ValidateFields($this->error);
        $validation->addCheck(new UsernameValidation($user, $this->entityManager));
        $validation->addCheck(new EmailValidation($user, $this->entityManager));
        $validation->addCheck(new PasswordValidation($user, $this->confirmPassword));
        $validation->check();
    }
}
