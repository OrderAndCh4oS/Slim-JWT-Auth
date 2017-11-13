<?php

namespace Tests\Functional;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class BaseAPITestCase extends TestCase
{

    protected $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = new Client(
            [
                'base_uri' => 'http://slim-jwt-auth.dev',
                'timeout' => 2,
                'http_errors' => false,
            ]
        );
    }

    /**
     * @param $response
     * @return mixed
     */
    protected function getData(Response $response)
    {
        $data = \GuzzleHttp\json_decode((string)$response->getBody());

        return $data;
    }
}
