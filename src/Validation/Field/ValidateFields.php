<?php

namespace Oacc\Validation\Field;

use Oacc\Exceptions\ValidationException;
use Oacc\Utility\Error;

class ValidateFields
{
    /**
     * @var Error
     */
    private $error;

    /**
     * @var array
     */
    private $checks = [];

    /**
     * Validate constructor.
     * @param $error
     */
    public function __construct($error)
    {
        $this->error = $error;
    }

    public function validate()
    {
        foreach ($this->checks as $check) {
            $check->validate($this->error);
        }
    }

    /**
     * @param FieldValidation $check
     */
    public function addCheck(FieldValidation $check)
    {
        $this->checks[] = $check;
    }

    /**
     * @throws ValidationException
     */
    public function checkValidation()
    {
        if ($this->error->hasErrors()) {
            throw new ValidationException($this->error);
        }
    }
}
