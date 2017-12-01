<?php

namespace Oacc\Utility;

use Lcobucci\JWT\Builder;
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
     * @param $data
     * @return string
     */
    public static function create($data)
    {
        $signer = new Sha256();
        $token = (new Builder())->setIssuer(getenv('JWT_ISSUER'))// Configures the issuer (iss claim)
        ->setAudience(getenv('JWT_AUDIENCE'))// Configures the audience (aud claim)
        ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
        ->setNotBefore(time())// Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + 3600);// Configures the expiration time of the token (exp claim)
        foreach ($data as $key => $value) {
            $token->set($key, $value);
        }

        return (string)$token->sign($signer, getenv('JWT_KEY'))->getToken();// creates a signature using key
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
}
