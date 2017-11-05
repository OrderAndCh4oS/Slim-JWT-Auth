<?php
$app->post('/register', 'Oacc\Controller\AuthController:registerAction')->setName('register');
$app->post('/login', 'Oacc\Controller\AuthController:loginAction')->setName('login');
$app->group(
    '/admin',
    function () {
        $this->get('', 'Oacc\Controller\UserController:indexAction')->setName('dashboard');
    }
)->add(new Oacc\Middleware\AuthMiddleware($container));
