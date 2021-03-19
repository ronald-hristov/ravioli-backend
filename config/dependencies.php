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
$container[\App\Service\FileService::class] =  function ($c) {return new \App\Service\FileService(ROOT_PATH);};
$container[\App\Service\Auth::class] = function ($c) {
    /** @var \Doctrine\ORM\EntityManager $em */
    $em = $c->get('em');
    $userid = $c->get('session')->get('userId');
    $isUserLoggedIn = (bool) $userid;
    /** @var \App\Entity\User $user */
    $user = null;
    if ($isUserLoggedIn) {
        $user = $em->getRepository(\App\Entity\User::class)->find($userid);
    }

    return new \App\Service\Auth($user);
};
$container[\App\Middleware\UserAwareRequestMiddleware::class] = function ($c) {return \App\Factory\UserAwareRequestMiddlewareFactory::create($c);};