<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

abstract class FieldValidation
{
    abstract public function validate(Error $error);
}
