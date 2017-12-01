<?php

namespace Tests\Mock;

/**
 * Class UserTest
 * @package Tests\Mock
 */
class UserTest extends BaseEnvironmentTestCase
{
    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testUserPostWithValidData()
    {
        $data = [
            "username" => "TestName",
            "email" => "testemail@test.com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $this->request('POST', '/user', $data);
        $this->successfulResponse();
        $this->assertEquals(['TestName registered successfully'], $this->responseData()->messages);
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPostUserWithNoData()
    {
        $data = [];
        $this->request('POST', '/user', $data);
        $this->errorResponse();
        $this->assertEquals(
            ['No valid data. Post username, email, password and password_confirm as json'],
            $this->responseData()->errors->validation
        );
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPostRegisterWithEmptyData()
    {
        $data = [
            "username" => "",
            "email" => "",
            "password" => "",
            "password_confirm" => "",
        ];
        $this->request('POST', '/user', $data);
        $this->errorResponse();
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPostRegisterWithInvalidData()
    {
        $username = "";
        for ($i = 81; $i >= 0; $i--) {
            $username .= "a";
        }
        $data = [
            "username" => $username,
            "email" => "testemail",
            "password" => "aaaaaaaa",
            "password_confirm" => "bbbbbbbb",
        ];
        $this->request('POST', '/user', $data);
        $this->errorResponse();
        $this->assertEquals(['Username is too long, maximum 80 characters'], $this->responseData()->errors->username);
        $this->assertEquals(['Please enter a valid email address'], $this->responseData()->errors->email);
        $this->assertEquals(['Passwords do not match'], $this->responseData()->errors->password_confirm);
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPostRegisterWithUsedData()
    {
        $data = [
            "username" => "TestName",
            "email" => "testemail@test.com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $this->request('POST', '/user', $data);
        $this->errorResponse();
        $this->assertEquals(
            ['Email is not available'],
            $this->responseData()->errors->email
        );
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPostLoginWithValidData()
    {
        $data = [
            "username" => "TestName",
            "password" => "aaaaaaaa",
        ];
        $this->request('POST', '/login', $data);
        $this->successfulResponse();
        $this->assertObjectHasAttribute('data', $this->responseData());
        $this->assertEquals(['Logged in'], $this->responseData()->messages);
        $this->assertObjectHasAttribute('token', $this->responseData()->data);
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPostLoginWithEmptyData()
    {
        $data = [
            "username" => "",
            "password" => "",
        ];
        $this->request('POST', '/login', $data);
        $this->errorResponse();
        $this->assertEquals(['Missing username'], $this->responseData()->errors->username);
        $this->assertEquals(['Missing password'], $this->responseData()->errors->password);
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPostLoginWithInvalidData()
    {
        $data = [
            "username" => "TestNameNotReal",
            "password" => "cccccccc",
        ];
        $this->request('POST', '/login', $data);
        $this->errorResponse();
        $this->assertEquals(['Invalid credentials, login failed'], $this->responseData()->errors->auth);
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testGetUserWithValidData()
    {
        $this->request('get', '/user', [], $this->getAuthHeader('TestName'));
        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json;charset=utf-8');
        $this->assertObjectHasAttribute('status', $this->responseData());
        $this->assertObjectHasAttribute('data', $this->responseData());
        $this->assertObjectHasAttribute('id', $this->responseData()->data->user->data);
        $this->assertObjectHasAttribute('username', $this->responseData()->data->user->data);
        $this->assertObjectHasAttribute('email', $this->responseData()->data->user->data);
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testGetAdminWithNoToken()
    {
        $this->request('get', '/user', []);
        $this->errorResponse();
        $this->assertEquals(
            'Token not found',
            $this->responseData()->errors->auth
        );
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testGetAdminWithInvalidTokenData()
    {
        $this->request('get', '/user', [], $this->getAuthHeader('bad_username', ['ROLE_NONE']));
        $this->errorResponse(401);
        $this->assertEquals(
            'Invalid credentials, login failed',
            $this->responseData()->errors
        );
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testUserPutWithValidData()
    {
        $data = [
            "username" => "TestNameTwo",
            "email" => "testemailtwo@test.com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $this->request('PUT', '/user', $data, $this->getAuthHeader('TestName'));
        $this->successfulResponse();
        $this->assertEquals(['TestNameTwo updated successfully'], $this->responseData()->messages);
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testUserPutWithUnchangedUsernameAndPassword()
    {
        $data = [
            "username" => "TestNameTwo",
            "email" => "testemailtwo@test.com",
            "password" => "bbbbbbbb",
            "password_confirm" => "bbbbbbbb",
        ];
        $this->request('PUT', '/user', $data, $this->getAuthHeader('TestNameTwo'));
        $this->successfulResponse();
        $this->assertEquals(['TestNameTwo updated successfully'], $this->responseData()->messages);
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testUserPutWithInvalidData()
    {
        $data = [
            "username" => "&*)$#**)(!&%@_*%",
            "email" => "notvalid",
            "password" => "aaaa",
            "password_confirm" => "bbbbbbbb",
        ];
        $this->request('PUT', '/user', $data, $this->getAuthHeader('TestNameTwo'));
        $this->errorResponse();
        $this->assertEquals(
            ['Password is too short, minimum 8 characters'],
            $this->responseData()->errors->password
        );
    }
}
