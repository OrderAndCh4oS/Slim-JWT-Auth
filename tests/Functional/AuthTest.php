<?php

namespace Tests\Functional;

use Oacc\Authentication\Jwt;

class AuthTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
     */
    public function testPostRegisterWithValidData()
    {
        $data = [
            "username" => "TestName_".uniqid(),
            "email" => "testemail@".uniqid().".com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $response = $this->client->request(
            'post',
            'register',
            [
                'json' => $data,
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
     */
    public function testPostLoginWithValidData()
    {
        $data = [
            "username" => "TestName",
            "password" => "aaaaaaaa",
        ];
        $response = $this->client->request(
            'post',
            'login',
            [
                'json' => $data,
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
     */
    public function testGetAdminWithValidData()
    {
        $token = Jwt::create('TestName', ['ROLE_USER']);
        $response = $this->client->request(
            'get',
            'admin',
            [
                'headers' => [
                    'Authorization' => "Bearer ".$token,
                ],
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }
}
