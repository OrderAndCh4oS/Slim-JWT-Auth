<?php

namespace Oacc\Session;

/**
 * Class Error
 * @package Oacc\Message
 */
class Error extends Data
{
    /**
     * @param $name
     * @param $message
     */
    public function setError($name, $message)
    {
        $this->setData($name, $message);
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->hasData();
    }
}
