<?php

namespace Validation;

use Oacc\Entity\User;
use Oacc\Validation\Exceptions\ValidationException;
use Oacc\Validation\UserValidationListener;
use Oacc\Validation\ValidationListener;
use PHPUnit\Framework\TestCase;
use Tests\BaseTestCase;

class UserValidationListenerTest extends BaseTestCase
{
    public function testUserValidation()
    {
        $user = new User();
        $user->setUsername('username');
        $user->setEmailAddress('an@email.address');
        $user->setPlainPassword('aaaa');
        $userValidation = new UserValidationListener('aaaa', $this->entityManager);
        $userValidation->validation($user);
        $this->assertTrue(true);
    }

    public function testUserValidationInvalid()
    {
        $user = new User();
        $user->setUsername('');
        $user->setEmailAddress('notemail');
        $user->setPlainPassword('');
        $this->expectException(ValidationException::class);
        $userValidation = new UserValidationListener('aaaa', $this->entityManager);
        $userValidation->validation($user);
    }
}
