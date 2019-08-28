<?php

namespace App\inventory;


class processCSV {

    public function returnJson($csv){

        $csvdata =[];

        if(!is_dir('../app/csv')){
            mkdir('../app/csv');
        }
        $now= date('y.m.d.h.s');
        $target_path = "../app/csv/";
        $uploadFile = $csv->getClientFilename();
        $extension = pathinfo($uploadFile);
        $extension = $extension['extension'];
        $uploadFileName = 'inventory-'.$now.'.'.$extension;
        $instr1 = $target_path . trim($uploadFileName);
        $instr = str_replace(' ', '_', $instr1);
        $csv->moveTo($instr);

        if(file_exists($instr)){
            $csvdata = array_map('str_getcsv', file($instr));

            array_walk($csvdata, function(&$a) use ($csvdata) {
                $openingDay = $a[1];
                $genre = preg_replace("/[^a-zA-Z]/", "", $a[2]);
                $lastDay = date('Y-m-d', strtotime($openingDay. '+100 days'));
                $salesStartDay = date('Y-m-d', strtotime($openingDay. '-25 days'));
                $salesEndDay = date('Y-m-d', strtotime($lastDay. '-5 days'));
                $a = [
                    'title' => $a[0],
                    'opening_day' => $openingDay,
                    'genre' => $genre,
                    'last_day' => $lastDay,
                    'sales_start_day' => $salesStartDay,
                    'sales_end_day' => $salesEndDay
                ];
            });

        }

        return $csvdata;

    }

    public function dbInsert($records, $db){

        //$cleanGenre = $db->prepare("Delete FROM shows");
        // $cleanGenre->execute();

        foreach ($records as $record ){

            $queryGenre = $db->prepare("SELECT id FROM ticket_genres where genre =:genre");
            $queryGenre->bindParam("genre", $record['genre']);
            $queryGenre->execute();
            $new = $queryGenre->fetch();
            $genreId = $new['id'];

            if($genreId){
                $insertRecordsSql = "INSERT INTO shows (title,genre_id,opening_day,last_day,sales_start_day,sales_end_day) 
                  VALUES(:title,:genre_id,:opening_day,:last_day,:sales_start_day,:sales_end_day)";
                $insert = $db->prepare($insertRecordsSql);
                $insert->bindParam("title", $record['title']);
                $insert->bindParam("genre_id", $genreId);
                $insert->bindParam("opening_day", $record['opening_day']);
                $insert->bindParam("last_day", $record['last_day']);
                $insert->bindParam("sales_start_day", $record['sales_start_day']);
                $insert->bindParam("sales_end_day", $record['sales_end_day']);
                $insert->execute();
            }
        }

        return $records;
    }

}