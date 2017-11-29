<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class Regex extends FieldValidation
{
    private $string;
    private $regex;

    public function __construct($string, $regex)
    {
        $this->string = $string;
        $this->regex = $regex;
    }

    public function validate(Error $error)
    {
        if (preg_match($this->regex, $this->string)) {
            $error->addError(ucfirst($error->getName()).' can only contain letters, numbers, underscores and hyphens');
        }
    }
}
