<?php

namespace Oacc\Message;

/**
 * Class Error
 * @package Oacc\Message
 */
class Error extends Message
{
    /**
     * @param $name
     * @param $message
     */
    public function setError($name, $message)
    {
        $this->setMessage($name, $message);
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->hasMessage();
    }
}
