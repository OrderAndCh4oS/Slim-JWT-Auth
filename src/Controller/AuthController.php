<?php

namespace Oacc\Controller;

use Oacc\Authentication\Exceptions\AuthenticationException;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller
{
    public function indexAction(Request $request, Response $response, $args = [])
    {
        if ($request->isPost()) {
            $credentials = [
                'username' => $request->getParam('username'),
                'password' => $request->getParam('password'),
            ];
            try {
                $user = $this->auth->authenticate($credentials);
                $this->auth->login($user);
            } catch (AuthenticationException $exception) {
                $this->error->setError('auth', $exception->getMessage());

                return $response->withRedirect($this->router->pathFor('login'));
            }

            return $response->withRedirect($this->router->pathFor('dashboard'));
        }

        return $this->view->render($response, 'auth/index.twig');
    }

    public function registerAction(Request $request, Response $response, $args = [])
    {
        if ($request->isPost()) {
            $user = $this->auth->register($request);
            if ($this->error->hasErrors()) {
                return $response->withRedirect($this->router->pathFor('register'));
            }
            $this->message->setMessage('success', 'You have successfully registered');

            return $response->withRedirect($this->router->pathFor('login'));
        }

        return $this->view->render($response, 'auth/register.twig');
    }

    public function logoutAction(Request $request, Response $response, $args = [])
    {
        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('login'));
    }
}
