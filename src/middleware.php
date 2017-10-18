<?php
// Application middleware
$app->add(new \RKA\SessionMiddleware(['name' => 'MejuicerSessionStorage']));
$app->add(new \Oacc\Middleware\ErrorsMiddleware($container));
