<?php

use App\inventory;

$app->post('/inventory/checkInventory', function ($request, $response) {
    $files = $request->getUploadedFiles();
    $input = $request->getParsedBody();
    $csv = $files['csv'];

    $the_big_array = inventory\processCSV::returnJson($csv);

    return $this->response->withJson($the_big_array);
});