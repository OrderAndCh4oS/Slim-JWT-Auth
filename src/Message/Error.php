<?php

namespace Oacc\Message;

use RKA\Session;

class Error extends Message
{
    public function __construct(Session $session = null)
    {
        parent::__construct($session);
    }

    public function setError($name, $message)
    {
        $this->setMessage($name, $message);
    }

    public function hasErrors()
    {
        return $this->hasMessage();
    }
}
