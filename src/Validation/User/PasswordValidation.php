<?php

namespace Oacc\Validation\User;

use Oacc\Entity\User;
use Oacc\Utility\Error;
use Oacc\Validation\Field\FieldValidation;
use Oacc\Validation\Field\Length;
use Oacc\Validation\Field\NotEmpty;
use Oacc\Validation\Field\PasswordConfirm;
use Oacc\Validation\Field\ValidateFields;

/**
 * Class PasswordValidation
 * @package Oacc\Validation\User
 */
class PasswordValidation extends FieldValidation
{

    /**
     * @var User
     */
    private $user;
    /**
     * @var
     */
    private $confirmPassword;

    /**
     * PasswordValidation constructor.
     * @param User $user
     * @param $confirmPassword
     */
    public function __construct(User $user, $confirmPassword)
    {
        $this->user = $user;
        $this->confirmPassword = $confirmPassword;
    }

    /**
     * @param Error $error
     * @return mixed|void
     */
    public function runCheck(Error $error)
    {
        $password = $this->user->getPlainPassword();
        $error->setName('password');
        $validate = new ValidateFields($error);
        $validate->addCheck(new NotEmpty($password))
            ->addCheck(new Length($password, 255, 8))
            ->addCheck(new PasswordConfirm($password, $this->confirmPassword))
            ->runChecks();
    }
}
