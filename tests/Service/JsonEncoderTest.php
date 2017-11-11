<?php

namespace Service;

use Oacc\Service\JsonEncoder;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;

class JsonEncoderTest extends TestCase
{
    public function testSuccessJSON()
    {
        /** @var Response $response */
        $response = JsonEncoder::setSuccessJson(new Response(), ['hello'], ['user'], 201);
        $this->assertInstanceOf("\Slim\Http\Response", $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('{"status":"success","data":["user"],"messages":["hello"]}', (string)$response->getBody());
    }

    public function testErrorJSON()
    {
        /** @var Response $response */
        $response = JsonEncoder::setErrorJson(new Response(), ['error!!!'], 403);
        $this->assertInstanceOf("\Slim\Http\Response", $response);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('{"status":"error","errors":["error!!!"]}', (string)$response->getBody());
    }
}
