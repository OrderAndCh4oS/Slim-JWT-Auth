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
    private $name;

    /**
     * Error constructor.
     * @param array $errors
     */
    public function __construct($errors = null)
    {
        if ($errors) {
            $this->errors = $errors;
        }
        $this->name = 'default';
    }

    /**
     * @param $message
     * @param string $name
     */
    public function addError($message, $name = null)
    {
        if (!$name) {
            $name = $this->name;
        }
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
