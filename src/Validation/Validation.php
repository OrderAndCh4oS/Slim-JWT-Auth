<?php

namespace Oacc\Validation;

use Oacc\Exceptions\ValidationException;
use Oacc\Utility\Error;

/**
 * Class EntityValidation
 * @package Oacc\Validation\Entity
 */
abstract class Validation
{

    /**
     * @var Error
     */
    protected $error;

    /**
     * EntityValidation constructor.
     */
    public function __construct()
    {
        $this->error = new Error();
    }

    /**
     * @param $property
     * @return mixed
     */
    abstract public function validate($property);

    /**
     * @throws ValidationException
     */
    public function checkErrors()
    {
        if ($this->error->hasErrors()) {
            throw new ValidationException($this->error);
        }
    }
}
