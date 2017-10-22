<?php
$app->get('/', 'Oacc\Controller\PageController:indexAction')->setName('home');
$app->post('/register', 'Oacc\Controller\AuthController:registerAction')->setName('register');
$app->post('/login', 'Oacc\Controller\AuthController:indexAction')->setName('login');
$app->post('/logout', 'Oacc\Controller\AuthController:logoutAction')->setName('logout');
$app->group(
    '/admin',
    function () {
        $this->get('', 'Oacc\Controller\AdminController:indexAction')->setName('dashboard');
    }
)->add(new Oacc\Middleware\AuthMiddleware($container));
