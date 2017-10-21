<?php

namespace Oacc\Validation\Exceptions;

/**
 * Class ValidationException
 * @package Oacc\Validation\Exceptions
 */
class ValidationException extends \Exception
{
    /**
     * ValidationException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
