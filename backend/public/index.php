<?php
declare(strict_types=1);

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}


require __DIR__ . '/../vendor/autoload.php';


// Instantiate the app
$settings = require __DIR__ . '/../app/config/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/config/dependencies.php';;
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/../app/config/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../app/config/routes.php';
$routes($app);

// Run app
$app->run();