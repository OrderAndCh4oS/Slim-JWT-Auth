<?php

namespace Oacc\Listener\Validation\Exceptions;

use Oacc\Utility\Error;

/**
 * Class ValidationException
 * @package Oacc\Validation\Exceptions
 */
class ValidationException extends \Exception
{
    /**
     * @var Error $errors ;
     */
    private $errors;

    /**
     * ValidationException constructor.
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
     * @return array
     */
    public function getErrors()
    {
        return $this->errors->getErrors();
    }
}
