<?php

namespace Transformer;

use Oacc\Entity\User;
use Oacc\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;

class UserTransformerTest extends TestCase
{
    /**
     * @var UserTransformer $userTransformer
     */
    private $userTransformer;
    private $user;

    public function testUserTransformer()
    {
        $this->assertEquals(
            [
                'id' => 0,
                'username' => 'test',
                'email' => 'test@test.com',
            ],
            $this->userTransformer->transform($this->user)
        );
    }

    protected function setUp()
    {
        $this->user = new User();
        $this->user->setUsername('test');
        $this->user->setEmailAddress('test@test.com');
        $this->userTransformer = new UserTransformer();
    }
}
