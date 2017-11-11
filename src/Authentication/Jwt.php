<?php

namespace Oacc\Authentication;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Oacc\Error\Error;
use Oacc\Validation\Exceptions\ValidationException;
use Slim\Http\Request;

class Jwt
{
    public static function create($username, $roles)
    {
        $signer = new Sha256();
        $token = (new Builder())->setIssuer('http://slim-jwt-auth.dev')// Configures the issuer (iss claim)
        ->setAudience('http://vue-client.dev')// Configures the audience (aud claim)
        ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
        ->setNotBefore(time())// Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + 3600)// Configures the expiration time of the token (exp claim)
        ->set('username', $username)// Configures a new claim, called "username"
        ->set('roles', $roles)// Configures a new claim, called "roles"
        ->sign($signer, '**06-russia-STAY-dollar-95**')// creates a signature using key
        ->getToken(); // Retrieves the generated token

        return (string)$token;
    }

    /**
     * @param Request $request
     * @return Token
     * @throws ValidationException
     */
    public static function get(Request $request)
    {
        $bearer = $request->getHeader('Authorization');
        $regex = '/^Bearer\s/';
        $tokenHash = preg_replace($regex, '', $bearer);
        if (!empty($tokenHash)) {
            return (new Parser())->parse($tokenHash[0]);
        } else {
            throw new ValidationException(new Error(['Token not found']));
        }
    }

    public static function check(Token $token)
    {
        $signer = new Sha256();
        if (!$token->verify($signer, '**06-russia-STAY-dollar-95**')) {
            throw new \InvalidArgumentException();
        }

        return $token->verify($signer, '**06-russia-STAY-dollar-95**');
    }
}
