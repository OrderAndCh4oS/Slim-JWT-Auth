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
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('messages', $this->responseData());
    }

    public function testPostUserWithNoData()
    {
        $data = [];
        $this->request('POST', '/user', $data);
        $this->assertThatResponseHasStatus(400);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('errors', $this->responseData());
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
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('errors', $this->responseData());
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
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('errors', $this->responseData());
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
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('messages', $this->responseData());
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
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('errors', $this->responseData());
    }

    public function testGetAdminWithValidData()
    {
        $this->request('get', '/user', [], $this->authHeader);
        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('data', $this->responseData());
    }

    public function testGetAdminWithNoToken()
    {
        $this->request('get', '/user', []);
        $this->assertThatResponseHasStatus(400);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('errors', $this->responseData());
    }

    public function testUserPutWithValidData()
    {
        $data = [
            "username" => "TestNameThree",
            "email" => "testemailthree@test.com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $this->request('PUT', '/user', $data, $this->authHeader);
        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('messages', $this->responseData());
    }
}
