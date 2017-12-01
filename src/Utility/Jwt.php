<?php

namespace Oacc\Utility;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;

/**
 * Class Jwt
 * @package Oacc\Utility
 */
class Jwt
{

    /**
     * @param Token $token
     * @return bool
     */
    public static function check(Token $token)
    {
        $signer = new Sha256();
        if (!$token->verify($signer, getenv('JWT_KEY'))) {
            throw new \InvalidArgumentException('Token Invalid');
        }

        return $token->verify($signer, getenv('JWT_KEY'));
    }

    /**
     * @param $authorizationHeader
     * @return Token
     */
    public static function get($authorizationHeader)
    {
        $regex = '/^Bearer\s/';
        $tokenHash = preg_replace($regex, '', $authorizationHeader);
        if (!empty($tokenHash)) {
            return (new Parser())->parse($tokenHash[0]);
        } else {
            throw new \InvalidArgumentException('Token not found');
        }
    }
}
