<?php

namespace Oacc\Validation;

use Oacc\Entity\User;
use Oacc\Utility\Error;
use Oacc\Validation\Field\FieldValidation;

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
        switch (true) {
            case empty($password):
                $error->addError('password', 'Please enter a password');
                break;
            case strlen($password) < 8:
                $error->addError('password', 'Password must contain a minimum of 8 characters');
                break;
            case $password != $this->confirmPassword:
                $error->addError('password_confirm', 'Passwords do not match');
                break;
        }
    }
}
