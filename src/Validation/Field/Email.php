<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class Email extends FieldValidation
{
    /**
     * @var
     */
    private $email;

    /**
     * NotEmptyValidation constructor.
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @param Error $error
     */
    public function validate(Error $error)
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $error->addError('Please enter a valid email address');
        }
    }
}
