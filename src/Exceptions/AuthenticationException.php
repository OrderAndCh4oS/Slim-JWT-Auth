<?php

namespace Oacc\Exceptions;

/**
 * Class AuthenticationException
 * @package Oacc\Exceptions
 */
class AuthenticationException extends \Exception
{
    /**
     * AuthenticationException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
