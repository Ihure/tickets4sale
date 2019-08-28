<?php

use App\inventory;


$app->post('/inventory/checkInventory', function ($request, $response) {
    $files = $request->getUploadedFiles();
    $input = $request->getParsedBody();
    $csv = isset($files['csv']) ? $files['csv'] : '';
    $web = isset($input['web']) ? 1 : 0;

    if($csv){
        $processedCsvData = inventory\processCSV::returnJson($csv);

        inventory\processCSV::dbInsert($processedCsvData, $this->db);
    }

    $queryShows = $this->db->prepare("
            SELECT 
                s.id, s.title, s.opening_day, s.last_day, g.genre, g.price
            FROM
                shows s
            INNER JOIN
                ticket_genres g ON g.id = s.genre_id
            WHERE
                s.last_day >=:sdate and s.opening_day <=:sdate");
    $queryShows->bindParam("sdate", $input['show_date']);
    $queryShows->execute();
    $shows = $queryShows->fetchAll();

    $data = inventory\processShows::getAvailableTickets($shows,$input['show_date'],$input['query_date']);

    $response = $web == 1 ? inventory\processShows::group_by_web($data): inventory\processShows::group_by_rest($data);

    return $this->response->withJson($response);
});