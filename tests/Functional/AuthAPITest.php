<?php

namespace Tests\Functional;

use Oacc\Authentication\Jwt;

class AuthAPITest extends BaseAPITestCase
{
    public function testPostRegisterWithValidData()
    {
        $data = [
            "username" => "TestName",
            "email" => "testemail@test.com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $response = $this->client->request('post', 'user', ['json' => $data]);
        $data = $this->getData($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data->status);
        $this->assertObjectHasAttribute('messages', $data);
    }

    public function testPostRegisterWithNoData()
    {
        $data = [];
        $response = $this->client->request('post', 'user', ['json' => $data]);
        $data = $this->getData($response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('error', $data->status);
        $this->assertObjectHasAttribute('errors', $data);
    }

    public function testPostRegisterWithInvalidData()
    {
        $data = [
            "username" => "TestName",
            "email" => "testemail",
            "password" => "aaaaaaaa",
            "password_confirm" => "bbbbbbbb",
        ];
        $response = $this->client->request('post', 'user', ['json' => $data]);
        $data = $this->getData($response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('error', $data->status);
        $this->assertObjectHasAttribute('errors', $data);
        $this->assertObjectHasAttribute('username', $data->errors);
        $this->assertObjectHasAttribute('email', $data->errors);
        $this->assertObjectHasAttribute('password_confirm', $data->errors);
    }

    public function testPostLoginWithValidData()
    {
        $data = [
            "username" => "TestName",
            "password" => "aaaaaaaa",
        ];
        $response = $this->client->request('post', 'login', ['json' => $data]);
        $data = $this->getData($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data->status);
        $this->assertObjectHasAttribute('messages', $data);
    }

    public function testGetAdminWithValidData()
    {
        $token = Jwt::create('TestName', ['ROLE_USER']);
        $headers = ['Authorization' => "Bearer ".$token];
        $response = $this->client->request('get', 'user', ['headers' => $headers]);
        $data = $this->getData($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data->status);
        $this->assertObjectHasAttribute('data', $data);
        $this->assertObjectHasAttribute('user', $data->data);
    }
}
