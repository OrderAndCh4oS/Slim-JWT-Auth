<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class ValidateFields
 * @package Oacc\Validation\Field
 */
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
     *
     */
    public function runChecks()
    {
        foreach ($this->checks as $check) {
            $check->runCheck($this->error);
        }
    }

    /**
     * @param FieldValidation $check
     * @return ValidateFields
     */
    public function addCheck(FieldValidation $check)
    {
        $this->checks[] = $check;

        return $this;
    }
}
