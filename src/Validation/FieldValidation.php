<?php

namespace Oacc\Validation;

use Oacc\Utility\Error;

abstract class FieldValidation
{
    abstract public function validate(Error $error);
}
