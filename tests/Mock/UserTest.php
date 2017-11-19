<?php

namespace Tests\Mock;

use Oacc\Authentication\Jwt;
use Slim\Http\Response;

class UserTest extends BaseEnvironmentTestCase
{
    public function testUserPostWithValidData()
    {
        $data = [
            "username" => "TestNameTwo",
            "email" => "testemailtwo@test.com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $this->request('POST', '/user', $data);
        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertArrayHasKey('status', $this->responseData());
        $this->assertArrayHasKey('messages', $this->responseData());
    }

    public function testPostUserWithNoData()
    {
        $data = [];
        $this->request('POST', '/user', $data);
        $this->assertThatResponseHasStatus(400);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertArrayHasKey('status', $this->responseData());
        $this->assertArrayHasKey('errors', $this->responseData());
    }

    public function testPostRegisterWithInvalidData()
    {
        $data = [
            "username" => "TestName",
            "email" => "testemail",
            "password" => "aaaaaaaa",
            "password_confirm" => "bbbbbbbb",
        ];
        $this->request('POST', '/user', $data);
        $this->assertThatResponseHasStatus(400);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertArrayHasKey('status', $this->responseData());
        $this->assertArrayHasKey('errors', $this->responseData());
    }

    public function testPostRegisterWithUsedData()
    {
        $data = [
            "username" => "TestNameTwo",
            "email" => "testemailtwo@test.com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $this->request('POST', '/user', $data);
        $this->assertThatResponseHasStatus(400);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertArrayHasKey('status', $this->responseData());
        $this->assertArrayHasKey('errors', $this->responseData());
    }

    public function testPostLoginWithValidData()
    {
        $data = [
            "username" => "TestNameTwo",
            "password" => "aaaaaaaa",
        ];
        $this->request('POST', '/login', $data);
        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertArrayHasKey('status', $this->responseData());
        $this->assertArrayHasKey('messages', $this->responseData());
    }

    public function testPostLoginWithInvalidData()
    {
        $data = [
            "username" => "TestNameNotReal",
            "password" => "cccccccc",
        ];
        $this->request('POST', '/login', $data);
        $this->assertThatResponseHasStatus(400);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertArrayHasKey('status', $this->responseData());
        $this->assertArrayHasKey('errors', $this->responseData());
    }

    public function testGetAdminWithValidData()
    {
        $token = Jwt::create('TestNameTwo', ['ROLE_USER']);
        $headers = ['Authorization' => "Bearer ".$token];
        $this->request('get', '/user', [], $headers);
        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertArrayHasKey('status', $this->responseData());
        $this->assertArrayHasKey('data', $this->responseData());
    }

    public function testGetAdminWithNoToken()
    {
        $this->request('get', '/user', []);
        $this->assertThatResponseHasStatus(400);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertArrayHasKey('status', $this->responseData());
        $this->assertArrayHasKey('errors', $this->responseData());
    }

}
