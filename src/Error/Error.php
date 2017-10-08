<?php

namespace Oacc\Error;

class Error
{
    public static function setError($name, $message) {
        if (!self::hasErrors()) {
            $_SESSION['errors'] = [];
        }
        $_SESSION['errors'][$name][] = $message;
    }

    public static function hasErrors() {
        return array_key_exists('errors', $_SESSION);
    }
}