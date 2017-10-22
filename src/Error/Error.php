<?php

namespace Oacc\Error;

class Error
{

    private static $errors = [];

    public static function create()
    {
        return new Error();
    }

    /**
     * @param $name
     * @param $message
     */
    public function addError($name, $message)
    {
        self::$errors[$name][] = $message;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty(self::$errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
