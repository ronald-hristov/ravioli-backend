<?php
// Required for doctrine console commands

use Doctrine\ORM\Tools\Console\ConsoleRunner;

define('APP_PATH', __DIR__ . '/../app');
define('ROOT_PATH', __DIR__ . '/..');
define('DATA_PATH', __DIR__ . '/../app/data');

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require ROOT_PATH . '/vendor/autoload.php';

// Instantiate the app
$settings = require ROOT_PATH . '/config/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require ROOT_PATH . '/config/dependencies.php';


/** @var \Doctrine\ORM\EntityManager $em */
$em = $app->getContainer()->get('em');
return ConsoleRunner::createHelperSet($em);
