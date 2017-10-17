<?php

namespace Oacc\Controller;

use Oacc\Entity\User;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller
{
    public function indexAction(Request $request, Response $response, $args = []) {
        if ($request->isPost()) {
            $credentials = [
                'username' => $request->getParam('username'),
                'password' => $request->getParam('password')
            ];
            /** @var User $user */
            $user = $this->auth->authenticate($credentials);
            if ($user) {
                $this->auth->login($user);
                return $response->withRedirect($this->router->pathFor('dashboard'));
            } else {
                echo "Nope";die;
            }
        }
        return $this->view->render($response, 'auth/index.twig');
    }

    public function registerAction(Request $request, Response $response, $args = []) {
        if ($request->isPost()) {
            $user = $this->auth->register($request);
            if (!$user) {
                return $response->withRedirect($this->router->pathFor('register'));
            }
        }
        // ToDo: Flash message that sign up has been successful
        // ToDo: Send email with registration code to authenticate user
        return $this->view->render($response, 'auth/register.twig');
    }

    public function logoutAction(Request $request, Response $response, $args = []) {
        $this->auth->logout();
        return $this->view->render($response, 'index.twig');
    }
}