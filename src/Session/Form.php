<?php

namespace Oacc\Session;

/**
 * Class Form
 * @package Oacc\Form
 */
class Form extends Data
{

    /**
     * @param $name
     * @param $data
     */
    public function setForm($name, $data)
    {
        $this->setData($name, $data);
    }

    /**
     * @return bool
     */
    public function hasForm()
    {
        return $this->hasData();
    }
}
