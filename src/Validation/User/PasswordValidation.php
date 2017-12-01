<?php

namespace Oacc\Validation\User;

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
        $validate->addCheck(new NotEmpty($password))
            ->addCheck(new Length($password, 255, 8))
            ->addCheck(new PasswordConfirm($password, $this->confirmPassword))
            ->validate();
    }
}
