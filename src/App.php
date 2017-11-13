<?php

namespace Oacc;

class App
{
    private $app;

    public function __construct($settings)
    {
        $this->app = new \Slim\App($settings);
        $container = (new Dependencies($this->app->getContainer()))->getContainer();
        (new Routes($this->app, $container));
    }

    /**
     * @return \Slim\App
     */
    public function getApp(): \Slim\App
    {
        return $this->app;
    }
}
