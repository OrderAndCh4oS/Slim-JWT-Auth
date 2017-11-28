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

    /**
     * @throws ValidationException
     */
    public function check()
    {
        foreach ($this->checks as $check) {
            $check->validate($this->error);
        }
        if ($this->error->hasErrors()) {
            throw new ValidationException($this->error);
        }
    }

    /**
     * @param FieldValidation $check
     */
    public function addCheck(FieldValidation $check)
    {
        $this->checks[] = $check;
    }
}
