<?php

use App\Controller\IndexController;

// Routes
/** @var Slim\App $app */
$app->group('/api', function(\Slim\App $app) {
    $app->get('', IndexController::class . ':indexAction');
    $app->post('/user', \App\Controller\UserController::class . ':postAction');
    $app->post('/user/auth', \App\Controller\UserController::class . ':authenticateAction');
    $app->get('/user/logout', \App\Controller\UserController::class . ':logoutAction');
    // TODO add middleware if user is logged in
    $app->post('/article/{id}', \App\Controller\ArticleController::class . ':editAction')->add(\App\Middleware\CanEditMiddleware::class);
    $app->post('/article', \App\Controller\ArticleController::class . ':postAction');
    $app->get('/article', \App\Controller\ArticleController::class . ':getAction');
    $app->get('/article/{id}', \App\Controller\ArticleController::class . ':getAction');
    $app->delete('/article/{id}', \App\Controller\ArticleController::class . ':deleteAction')->add(\App\Middleware\CanEditMiddleware::class);

});