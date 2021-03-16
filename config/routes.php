<?php

use App\Controller\IndexController;

// Routes
/** @var Slim\App $app */
$app->get('/api', IndexController::class . ':indexAction');
