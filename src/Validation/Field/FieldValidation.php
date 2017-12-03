<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class FieldValidation
 * @package Oacc\Validation\Field
 */
abstract class FieldValidation
{
    /**
     * @param Error $error
     * @return mixed
     */
    abstract public function runCheck(Error $error);
}
