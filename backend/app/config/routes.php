<?php
declare(strict_types=1);

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function (Request $request, Response $response) {
        $this->logger->addInfo("Hello World");
        $response->getBody()->write('Hello world!');
        return $response;
    });

    require __DIR__ . '/../inventory/inventory_routes.php';

};