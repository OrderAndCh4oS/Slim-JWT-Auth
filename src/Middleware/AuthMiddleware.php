<?php

namespace Oacc\Middleware;

use Lcobucci\JWT\Token;
use Oacc\Authentication\Exceptions\AuthenticationException;
use Oacc\Authentication\Jwt;
use Oacc\Service\JsonEncoder;
use Oacc\Validation\Exceptions\ValidationException;
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
            $token = Jwt::get($request);
            Jwt::check($token);
            $this->hasAuthData($token);
            $this->hasAllowedRoles($token);
        } catch (ValidationException $e) {
            return JsonEncoder::setErrorJson($response, $e->getErrors(), 400);
        } catch (AuthenticationException $e) {
            return JsonEncoder::setErrorJson($response, [$e->getMessage()], 401);
        }
        $response = $next($request, $response);

        return $response;
    }

    /**
     * @param Token $token
     * @throws AuthenticationException
     */
    private function hasAuthData(Token $token)
    {
        if (!$token->hasClaim('username') && $token->hasClaim('roles')) {
            throw new AuthenticationException('Invalid credentials, login failed');
        }
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
