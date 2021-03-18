<?php

use App\Controller\IndexController;

// Routes
/** @var Slim\App $app */
$app->group('/api', function(\Slim\App $app) {
    $app->get('', IndexController::class . ':indexAction');
    $app->post('/user', \App\Controller\UserController::class . ':postAction');
    $app->post('/user/auth', \App\Controller\UserController::class . ':authenticateAction');
    $app->get('/user/logout', \App\Controller\UserController::class . ':logoutAction');
});