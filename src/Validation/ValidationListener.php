<?php

namespace Oacc\Validation;

use Doctrine\Common\EventSubscriber;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;

abstract class ValidationListener implements EventSubscriber
{

    /**
     * @var Error
     */
    protected $error;

    /**
     * UserValidationListener constructor.
     * @param Error $error
     */
    public function __construct(Error $error)
    {
        $this->error = $error;
    }

    public function checkErrors()
    {
        if ($this->error->hasErrors()) {
            throw new ValidationException();
        }
    }
}