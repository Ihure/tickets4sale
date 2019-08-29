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

    $response = $web == 1 ? $data : inventory\processShows::group_by_rest($data);

    return $this->response->withJson($response);
});

$app->post('/inventory/getTickets4Sale', function ($request, $response) {
    $input = $request->getParsedBody();

    $queryShows = $this->db->prepare("
            SELECT 
                s.id, s.title, s.opening_day, s.last_day, g.genre, g.price, ifnull(sum(f.tickets_sold),0) as sold
            FROM
                shows s
            INNER JOIN
                ticket_genres g ON g.id = s.genre_id
            LEFT JOIN
                ticket_sales f on f.show_id = s.id and f.show_date =:sdate
            WHERE
                s.last_day >=:sdate and s.opening_day <=:sdate
            Group by s.id");
    $queryShows->bindParam("sdate", $input['show_date']);
    $queryShows->execute();
    $shows = $queryShows->fetchAll();

    $data = inventory\processShows::getTicketsForSale($shows,$input['show_date'],$input['query_date']);

    return $this->response->withJson($data);
});

$app->post('/inventory/purchaseTicket', function ($request, $response) {
    $input = $request->getParsedBody();

    $amount = $input['amount'];
    $id = $input['id'];
    $showDate = $input['show_date'];
    $queryDate = $input['query_date'];

    $insertRecordsSql = "INSERT INTO ticket_sales (show_id,show_date,tickets_sold) 
                  VALUES(:show_id,:show_date,:tickets_sold)";
    $insert = $this->db->prepare($insertRecordsSql);
    $insert->bindParam("show_id", $id);
    $insert->bindParam("show_date", $showDate);
    $insert->bindParam("tickets_sold", $amount);
    $insert->execute();

    $queryShows = $this->db->prepare("
            SELECT 
                s.id, s.title, s.opening_day, s.last_day, g.genre, g.price, ifnull(sum(f.tickets_sold),0) as sold
            FROM
                shows s
            INNER JOIN
                ticket_genres g ON g.id = s.genre_id
            LEFT JOIN
                ticket_sales f on f.show_id = s.id and f.show_date =:sdate
            WHERE
                s.last_day >=:sdate and s.opening_day <=:sdate
            Group by s.id");
    $queryShows->bindParam("sdate", $showDate);
    $queryShows->execute();
    $shows = $queryShows->fetchAll();

    $data = inventory\processShows::getTicketsForSale($shows,$showDate,$queryDate);

    return $this->response->withJson($data);
});