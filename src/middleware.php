<?php
// Application middleware

// $app->add(new \Slim\Csrf\Guard);
$app->add(new \Oacc\Middleware\ValidationErrorsMiddleware($container));