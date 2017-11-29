<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class NotEmpty extends FieldValidation
{
    private $value;

    /**
     * NotEmptyValidation constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function validate(Error $error)
    {
        if (empty($this->value)) {
            $error->addError('Please enter '.ucfirst($error->getName()));
        }
    }
}
