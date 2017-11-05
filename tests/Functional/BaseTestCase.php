<?php

namespace Tests\Functional;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{

    protected $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = new Client(
            [
                'base_uri' => 'http://slim-jwt-auth.dev',
                'timeout' => 0.5,
            ]
        );
    }
}
