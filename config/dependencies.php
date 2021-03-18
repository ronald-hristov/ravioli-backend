<?php
// DIC configuration

/** @var Slim\App $app */

$container = $app->getContainer();

$container['logger'] = function ($c) {
    $logger = new \Monolog\Logger('my_logger');
    $fileHandler = new \Monolog\Handler\StreamHandler(ROOT_PATH . '/log/app.log');
    $logger->pushHandler($fileHandler);
    return $logger;
};

// View Object
$container['view'] = function ($c) {
    $phpView = new \Slim\Views\PhpRenderer(APP_PATH . '/view/');
    $phpView->setLayout('layout.phtml');

    return $phpView;
};

$container['em'] = function ($c) {return \App\Factory\EntityManagerFactory::create($c);};
$container['session'] = function ($c) {return new \SlimSession\Helper;};
