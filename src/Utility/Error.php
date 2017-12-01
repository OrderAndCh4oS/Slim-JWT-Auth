<?php

namespace Oacc\Utility;

/**
 * Class Error
 * @package Oacc\Utility
 */
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
     * @var string
     */
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
