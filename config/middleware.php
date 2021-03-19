<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
/** @var Slim\App $app */
$app->add(\App\Middleware\UserAwareRequestMiddleware::class);