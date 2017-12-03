<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class Regex extends FieldValidation
{
    /**
     * @var
     */
    private $string;
    /**
     * @var
     */
    private $regex;

    /**
     * Regex constructor.
     * @param $string
     * @param $regex
     */
    public function __construct($string, $regex)
    {
        $this->string = $string;
        $this->regex = $regex;
    }

    /**
     * @param Error $error
     * @return mixed|void
     */
    public function runCheck(Error $error)
    {
        if (preg_match($this->regex, $this->string)) {
            $error->addError(ucfirst($error->getName()).' can only contain letters, numbers, underscores and hyphens');
        }
    }
}
