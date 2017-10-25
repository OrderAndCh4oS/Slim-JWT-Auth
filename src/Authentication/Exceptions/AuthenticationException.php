<?php

namespace Oacc\Authentication\Exceptions;

use Oacc\Error\Error;

/**
 * Class AuthenticationException
 * @package Oacc\Authentication\Exceptions
 */
class AuthenticationException extends \Exception
{

    /**
     * @var Error $errors
     */
    private $errors;

    /**
     * AuthenticationException constructor.
     * @param Error $errors
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(Error $errors, $message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return Error
     */
    public function getErrors(): Error
    {
        return $this->errors;
    }
}
