<?php

namespace Oacc\Authentication;

use Oacc\Entity\User;

/**
 * Class UserPasswordEncoder
 * @package Oacc\Authentication
 */
class UserPasswordEncoder
{
    /**
     * @param User $user
     * @return bool|string
     */
    public function encodePassword(User $user)
    {
        return password_hash($user->getPlainPassword(), PASSWORD_BCRYPT);
    }

    /**
     * @param $plainPassword
     * @param $hash
     * @return bool
     */
    public function verifyPassword($plainPassword, $hash)
    {
        return password_verify($plainPassword, $hash);
    }
}
