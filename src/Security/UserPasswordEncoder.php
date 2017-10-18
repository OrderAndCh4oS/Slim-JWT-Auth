<?php

namespace Oacc\Security;

use Oacc\Entity\User;

class UserPasswordEncoder
{
    public function encodePassword(User $user)
    {
        return password_hash($user->getPlainPassword(), PASSWORD_BCRYPT);
    }

    public function verifyPassword($plainPassword, $hash)
    {
        return password_verify($plainPassword, $hash);
    }
}
