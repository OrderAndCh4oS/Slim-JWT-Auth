<?php

namespace Oacc\Controller;

use Oacc\Authentication\Exceptions\AuthenticationException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthController
 * @package Oacc\Controller
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface|static
     */
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
                $this->form->setData('username', $request->getParam('username'));
                $this->error->setError('auth', $exception->getMessage());

                return $response->withRedirect($this->router->pathFor('login'));
            }

            return $response->withRedirect($this->router->pathFor('dashboard'));
        }

        return $this->view->render($response, 'auth/index.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function registerAction(Request $request, Response $response, $args = [])
    {
        if ($request->isPost()) {
            $this->auth->register($request);
            if ($this->error->hasErrors()) {
                $this->form->setData('username', $request->getParam('username'));
                $this->form->setData('email', $request->getParam('email'));

                return $response->withRedirect($this->router->pathFor('register'));
            }
            $this->message->setMessage('success', 'You have successfully registered');

            return $response->withRedirect($this->router->pathFor('login'));
        }

        return $this->view->render($response, 'auth/register.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function logoutAction(Request $request, Response $response, $args = [])
    {
        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('login'));
    }
}
