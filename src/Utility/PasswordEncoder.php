<?php

namespace Oacc\Utility;

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
    public static function encodePassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    /**
     * @param $plainPassword
     * @param $hash
     * @return bool
     */
    public static function verifyPassword($plainPassword, $hash): bool
    {
        return password_verify($plainPassword, $hash);
    }
}
