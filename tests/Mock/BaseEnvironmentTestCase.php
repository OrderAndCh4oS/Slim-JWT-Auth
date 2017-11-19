<?php

namespace Tests\Mock;

use Oacc\App;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Uri;

class BaseEnvironmentTestCase extends TestCase
{

    /**
     * @var \Slim\App
     */
    protected $app;

    /**
     * @var Response
     */
    private $response;

    public function setUp()
    {
        $this->app = (new App())->getApp();
    }

    protected function request($method, $url, array $requestParameters = [], $additionalHeaders = [])
    {
        $request = $this->prepareRequest($method, $url, $requestParameters, $additionalHeaders);
        $response = new Response();
        $app = $this->app;
        $this->response = $app($request, $response);
    }

    protected function assertThatResponseHasStatus($expectedStatus)
    {
        $this->assertEquals($expectedStatus, $this->response->getStatusCode());
    }

    protected function assertThatResponseHasContentType($expectedContentType)
    {
        $this->assertContains($expectedContentType, $this->response->getHeader('Content-Type'));
    }

    protected function responseData()
    {
        return json_decode((string)$this->response->getBody(), true);
    }

    private function prepareRequest($method, $url, array $requestParameters, array $additionalHeaders)
    {
        $env = Environment::mock(
            [
                'SCRIPT_NAME' => '/index.php',
                'REQUEST_URI' => $url,
                'REQUEST_METHOD' => $method,
            ]
        );
        $parts = explode('?', $url);
        if (isset($parts[1])) {
            $env['QUERY_STRING'] = $parts[1];
        }
        $uri = Uri::createFromEnvironment($env);
        $headers = Headers::createFromEnvironment($env);
        foreach ($additionalHeaders as $key => $value) {
            $headers->add($key, $value);
        }
        $cookies = [];
        $serverParams = $env->all();
        $body = new RequestBody();
        $body->write(json_encode($requestParameters));
        $request = new Request($method, $uri, $headers, $cookies, $serverParams, $body);

        return $request->withHeader('Content-Type', 'application/json');
    }
}
