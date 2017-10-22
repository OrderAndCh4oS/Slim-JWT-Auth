<?php

namespace Oacc\Session;

/**
 * Class Message
 * @package Oacc\Message
 */
class Message extends Data
{

    /**
     * @param $name
     * @param $message
     */
    public function setMessage($name, $message)
    {
        $this->setData($name, $message);

    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        return $this->hasData();
    }
}
