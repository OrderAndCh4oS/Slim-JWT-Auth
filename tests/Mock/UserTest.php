<?php

namespace Tests\Mock;

use Oacc\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    /**
     * @var \Slim\App
     */
    protected $app;

    public function setUp()
    {
        $this->app = (new App())->getApp();
    }

    public function testUserPost()
    {
        $env = Environment::mock(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/test',
            ]
        );
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame((string)$response->getBody(), '{"status":"success","data":["hello"]}');
    }

}
