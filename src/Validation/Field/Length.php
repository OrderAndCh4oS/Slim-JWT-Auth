<?php

namespace Oacc\Validation\Field;

use Oacc\Utility\Error;

/**
 * Class UniqueFieldValidation
 * @package Validation
 */
class Length extends FieldValidation
{
    /**
     * @var string $string
     */
    private $string;

    /**
     * @var int $max
     */
    private $max;

    /**
     * @var int $min
     */
    private $min;

    /**
     * Length constructor.
     * @param $string
     * @param $max
     * @param int $min
     */
    public function __construct($string, $max, $min = 0)
    {
        $this->string = $string;
        $this->max = $max;
        $this->min = $min;
    }

    /**
     * @param Error $error
     * @return mixed|void
     */
    public function validate(Error $error)
    {
        if (strlen($this->string) < $this->min) {
            $error->addError(ucfirst($error->getName()).' is too short, minimum '.$this->min.' characters');
        } elseif (strlen($this->string) > $this->max) {
            $error->addError(ucfirst($error->getName()).' is too long, maximum '.$this->max.' characters');
        }
    }
}
