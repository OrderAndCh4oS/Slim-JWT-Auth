<?php

namespace Oacc\Utility;

/**
 * Class Error
 * @package Oacc\Utility
 */
class Error
{

    /**
     * @var array|null
     */
    private $errors = [];

    /**
     * Error constructor.
     * @param array $errors
     */
    public function __construct($errors = null)
    {
        if ($errors) {
            $this->errors = $errors;
        }
    }

    /**
     * @param $name
     * @param $message
     */
    public function addError($name, $message)
    {
        $this->errors[$name][] = $message;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
