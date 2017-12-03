<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class NotEmpty extends FieldValidation
{
    /**
     * @var
     */
    private $value;

    /**
     * NotEmptyValidation constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param Error $error
     * @return mixed|void
     */
    public function runCheck(Error $error)
    {
        if (empty($this->value)) {
            $error->addError('Please enter '.ucfirst($error->getName()));
        }
    }
}
