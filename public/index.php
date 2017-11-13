<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

$settings = require __DIR__ . '/../src/settings.php';
$app = (new Oacc\App($settings))->getApp();

// Run app
$app->run();
