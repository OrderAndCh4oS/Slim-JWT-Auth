<?php

namespace Oacc\Validation;

use Oacc\Entity\User;
use Oacc\Utility\Error;
use Oacc\Validation\Field\FieldValidation;
use Oacc\Validation\Field\Length;
use Oacc\Validation\Field\NotEmpty;
use Oacc\Validation\Field\PasswordConfirm;
use Oacc\Validation\Field\ValidateFields;

class PasswordValidation extends FieldValidation
{

    private $user;
    /**
     * @var
     */
    private $confirmPassword;

    public function __construct(User $user, $confirmPassword)
    {
        $this->user = $user;
        $this->confirmPassword = $confirmPassword;
    }

    public function validate(Error $error)
    {
        $password = $this->user->getPlainPassword();
        $error->setName('password');
        $validate = new ValidateFields($error);
        $validate->addCheck(new NotEmpty($password));
        $validate->addCheck(new Length($password, 255, 8));
        $validate->addCheck(new PasswordConfirm($password, $this->confirmPassword));
        $validate->validate();
    }
}
