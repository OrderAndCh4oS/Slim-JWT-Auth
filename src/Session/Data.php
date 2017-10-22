<?php

namespace Oacc\Session;

use RKA\Session;

class Data
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
     * Data constructor.
     * @param Session|null $session
     */
    public function __construct(Session $session = null)
    {
        $this->session = $session ?? new Session();
        $this->type = strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * @param $name
     * @param $data
     */
    public function setData($name, $data)
    {
        $dataBag = $this->session->dataBag;
        $dataBag[$this->type][$name] = $data;
        $this->session->dataBag = $dataBag;
    }

    /**
     * @param $name
     * @param $data
     */
    public function addData($name, $data)
    {
        $dataBag = $this->session->dataBag;
        $dataBag[$this->type][$name][] = $data;
        $this->session->dataBag = $dataBag;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        $dataBag = $this->session->dataBag;

        return isset($dataBag[$this->type]);
    }
}
