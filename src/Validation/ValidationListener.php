<?php

namespace Oacc\Validation;

use Doctrine\Common\EventSubscriber;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;

/**
 * Class ValidationListener
 * @package Oacc\Validation
 */
abstract class ValidationListener implements EventSubscriber
{

    /**
     * @var Error
     */
    protected $error;

    /**
     * UserValidationListener constructor.
     */
    public function __construct()
    {
        $this->error = new Error();
    }

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
