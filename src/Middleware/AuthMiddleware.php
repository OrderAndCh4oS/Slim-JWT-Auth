<?php

namespace Oacc\Middleware;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Oacc\Service\JsonEncoder;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthMiddleware
 * @package Oacc\Middleware
 */
class AuthMiddleware extends Middleware
{
    /**
     * @var array
     */
    private $allowedRoles;

    /**
     * AuthMiddleware constructor.
     * @param Container $container
     * @param array $allowedRoles
     */
    public function __construct(Container $container, $allowedRoles = ['ROLE_USER'])
    {
        parent::__construct($container);
        $this->allowedRoles = $allowedRoles;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return Response|static
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $bearer = $request->getHeader('Authorization');
        $re = '/^Bearer\s/';
        $tokenHash = preg_replace($re, '', $bearer);
        $token = (new Parser())->parse((string)$tokenHash);
        $signer = new Sha256();
        $isValidToken = $token->verify($signer, '**06-russia-STAY-dollar-95**');
        if (!$isValidToken) {
            return JsonEncoder::setErrorJson($response, ['Not Authorised'], 401);
        }
        // ToDo: check user role, find user by uid;
        $response = $next($request, $response);

        return $response;
    }

    /**
     * @param Token $token
     * @return bool
     */
    private function hasAuthData(Token $token)
    {
        return $token->hasClaim('username') && $token->hasClaim('roles');
    }

    /**
     * @param Token $token
     * @return bool
     */
    private function hasAllowedRoles(Token $token)
    {
        return !empty(array_intersect($this->allowedRoles, $token->getClaim('roles')));
    }
}
