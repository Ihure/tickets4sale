<?php
declare(strict_types=1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->get('/', function (Request $request, Response $response) {
    $this->logger->addInfo("Hello World");
    $response->getBody()->write('Hello world!');
    return $response;
});

