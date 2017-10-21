<?php

namespace Oacc\Controller;

use Oacc\Entity\User;
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
            if (empty($credentials['username'])) {
                $this->error->setError('username', 'Enter your username');
            }
            if (empty($credentials['password'])) {
                $this->error->setError('password', 'Enter your password');
            }
            /** @var User $user */
            $user = $this->auth->authenticate($credentials);
            if ($user) {
                $this->auth->login($user);

                return $response->withRedirect($this->router->pathFor('dashboard'));
            } else {
                $this->error->setError('auth', 'Invalid login details');

                return $response->withRedirect($this->router->pathFor('login'));
            }
        }

        return $this->view->render($response, 'auth/index.twig');
    }

    public function registerAction(Request $request, Response $response, $args = [])
    {
        if ($request->isPost()) {
            $user = $this->auth->register($request);
            if (!$user) {
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
