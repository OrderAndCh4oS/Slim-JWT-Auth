<?php

namespace Entity;

use DateTime;
use Oacc\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User $user
     */
    private $user;

    public function testGettersAndSetters()
    {
        $this->user->setUsername("user one");
        $this->assertEquals("user one", $this->user->getUsername());
        $this->user->setEmailAddress("hello@test.com");
        $this->assertEquals("hello@test.com", $this->user->getEmailAddress());
        $this->user->setPassword('aaaaaaaa');
        $this->assertEquals("aaaaaaaa", $this->user->getPassword());
        $this->user->setPlainPassword('aaaaaaaa');
        $this->assertEquals("aaaaaaaa", $this->user->getPlainPassword());
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertEquals(['ROLE_ADMIN'], $this->user->getRoles());
        $this->user->setCreatedAt();
        $this->assertInstanceOf("DateTime", $this->user->getCreatedAt());
        $this->user->setUpdatedAt();
        $this->assertInstanceOf("DateTime", $this->user->getUpdatedAt());
        $this->user->setLastLogin(new DateTime());
        $this->assertInstanceOf("DateTime", $this->user->getLastLogin());
    }

    public function testEraseCredentials()
    {
        $this->assertEquals(null, $this->user->eraseCredentials());
        $this->assertEquals(null, $this->user->getPlainPassword());
    }

    protected function setUp()
    {
        $this->user = new User();
    }
}
