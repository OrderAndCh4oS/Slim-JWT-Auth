<?php

namespace Oacc;

use Dotenv\Dotenv;

/**
 * Class App
 * @package Oacc
 */
class App
{
    /**
     * @var \Slim\App
     */
    private $app;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $dotEnv = new Dotenv(realpath(__DIR__.'/..'));
        $dotEnv->load();
        $dotEnv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
        $settings = require __DIR__.'/settings.php';
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
