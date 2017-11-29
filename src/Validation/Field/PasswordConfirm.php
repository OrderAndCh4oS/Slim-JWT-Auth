<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class PasswordConfirm extends FieldValidation
{
    private $password;
    private $confirmPassword;

    /**
     * NotEmptyValidation constructor.
     * @param $password
     * @param $confirmPassword
     */
    public function __construct($password, $confirmPassword)
    {
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }

    public function validate(Error $error)
    {
        if ($this->password != $this->confirmPassword) {
            $error->addError('Passwords do not match', 'password_confirm');
        }
    }
}
