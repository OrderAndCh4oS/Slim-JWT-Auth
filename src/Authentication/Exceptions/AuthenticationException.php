<?php

namespace Oacc\Authentication\Exceptions;

/**
 * Class AuthenticationException
 * @package Oacc\Authentication\Exceptions
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
