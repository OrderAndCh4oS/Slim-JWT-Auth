<?php

namespace Oacc\Authentication\Password;

/**
 * Class UserPasswordEncoder
 * @package Oacc\Authentication
 */
class PasswordEncoder
{
    /**
     * @param $plainPassword
     * @return bool|string
     */
    public function encodePassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
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
