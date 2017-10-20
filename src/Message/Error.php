<?php

namespace Oacc\Message;

class Error extends Message
{
    public function setError($name, $message)
    {
        $this->setMessage($name, $message);
    }

    public function hasErrors()
    {
        return $this->hasMessage();
    }
}
