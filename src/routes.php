<?php
$app->get('/', 'Oacc\Controller\PageController:indexAction')->setName('home');
$app->map(['get', 'post'], '/register', 'Oacc\Controller\AuthController:registerAction')->setName('register');
$app->map(['get', 'post'], '/login', 'Oacc\Controller\AuthController:indexAction')->setName('login');
$app->get('/logout', 'Oacc\Controller\AuthController:logoutAction')->setName('logout');

$app->group(
    '/admin',
    function () {
        $this->get('', 'Oacc\Controller\AdminController:indexAction')->setName('dashboard');
    }
);