<?php

namespace Oacc\Validation;

use Doctrine\Common\EventSubscriber;
use Oacc\Message\Error;
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
     * @param Error $error
     */
    public function __construct(Error $error)
    {
        $this->error = $error;
    }

    /**
     * @throws ValidationException
     */
    public function checkErrors()
    {
        if ($this->error->hasErrors()) {
            throw new ValidationException();
        }
    }
}
