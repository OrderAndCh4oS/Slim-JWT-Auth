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
        $type = $this->type;
        $messages = $this->session->messageBag;
        $messages[$type][$name][] = $message;
        $this->session->messageBag = $messages;
    }

    public function hasMessage()
    {
        $type = $this->type;
        $messages = $this->session->messageBag;

        return isset($messages[$type]);
    }
}
