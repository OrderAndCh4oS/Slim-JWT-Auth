<?php

namespace Oacc\Middleware;

use Lcobucci\JWT\Token;
use Oacc\Exceptions\AuthenticationException;
use Oacc\Utility\JsonResponse;
use Oacc\Utility\Jwt;
use Psr\Container\ContainerInterface;
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
     * @param Container|ContainerInterface $container
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
        try {
            $token = Jwt::get($request->getHeader('Authorization'));
            Jwt::check($token);
            $this->hasAllowedRoles($token);
        } catch (\InvalidArgumentException $e) {
            return JsonResponse::setErrorJson($response, ['auth' => $e->getMessage()], 400);
        } catch (AuthenticationException $e) {
            return JsonResponse::setErrorJson($response, $e->getMessage(), 401);
        }
        $response = $next($request, $response);

        return $response;
    }

    /**
     * @param Token $token
     * @throws AuthenticationException
     */
    private function hasAllowedRoles(Token $token)
    {
        if (empty(array_intersect($this->allowedRoles, $token->getClaim('roles')))) {
            throw new AuthenticationException('Invalid credentials, login failed');
        }
    }
}
