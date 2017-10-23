<?php

namespace Oacc\Authentication;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class Jwt
{
    public static function create($uid = 1)
    {
        $signer = new Sha256();
        $token = (new Builder())->setIssuer('http://slim-jwt-auth.dev')// Configures the issuer (iss claim)
        ->setAudience('http://vue-client.dev')// Configures the audience (aud claim)
        ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
        ->setNotBefore(time())// Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + 3600)// Configures the expiration time of the token (exp claim)
        ->set('uid', $uid)// Configures a new claim, called "uid"
        ->sign($signer, '**06-russia-STAY-dollar-95**')// creates a signature using "testing" as key
        ->getToken(); // Retrieves the generated token

        return $token;
    }
}
