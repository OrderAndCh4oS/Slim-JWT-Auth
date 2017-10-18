<?php

namespace Oacc\Error;

use RKA\Session;

class Error
{
    /**
     * Session
     */
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function setError($name, $message)
    {
        $errors = $this->session->errors;
        $errors[$name][] = $message;
        $this->session->errors = $errors;
    }

    public function hasErrors()
    {
        return isset($this->session->errors);
    }
}
