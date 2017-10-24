<?php

namespace Oacc\Middleware;

use Lcobucci\JWT\Token;
use Oacc\Authentication\Jwt;
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
        $token = Jwt::get($request);
        if (!Jwt::check($token) || !$this->hasAuthData($token) || !$this->hasAllowedRoles($token)) {
            return JsonEncoder::setErrorJson($response, ['Not Authorised'], 401);
        }
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
