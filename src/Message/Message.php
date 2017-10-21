<?php

namespace Oacc\Message;

use RKA\Session;

/**
 * Class Message
 * @package Oacc\Message
 */
class Message
{
    /**
     * Session
     */
    protected $session;
    /**
     * @var string
     */
    protected $type;

    /**
     * Message constructor.
     * @param Session|null $session
     */
    public function __construct(Session $session = null)
    {
        $this->session = $session ?? new Session();
        $this->type = strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * @param $name
     * @param $message
     */
    public function setMessage($name, $message)
    {
        $messages = $this->session->messageBag;
        $messages[$this->type][$name][] = $message;
        $this->session->messageBag = $messages;
    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        $messages = $this->session->messageBag;

        return isset($messages[$this->type]);
    }
}
