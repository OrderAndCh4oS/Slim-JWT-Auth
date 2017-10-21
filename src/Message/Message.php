<?php

namespace Oacc\Message;

use RKA\Session;

class Message
{
    /**
     * Session
     */
    protected $session;
    protected $type;

    public function __construct(Session $session = null)
    {
        $this->session = $session ?? new Session();
        $this->type = strtolower((new \ReflectionClass($this))->getShortName());
    }

    public function setMessage($name, $message)
    {
        $messages = $this->session->messageBag;
        $messages[$this->type][$name][] = $message;
        $this->session->messageBag = $messages;
    }

    public function hasMessage()
    {
        $messages = $this->session->messageBag;

        return isset($messages[$this->type]);
    }
}
