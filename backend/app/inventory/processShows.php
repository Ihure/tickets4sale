<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 2019-08-28
 * Time: 1:49 PM
 */

namespace App\inventory;


class processShows {

    public function getAvailableTickets($shows,$showDate, $queryDate){

        $response = [];

        foreach ($shows as $show){
            $openingDate = strtotime($show['opening_day']);
            $show_date = strtotime($showDate);
            $query_date = strtotime($queryDate);

            $runDif = $show_date - $openingDate;
            $runDay = round($runDif/(60*60*24));
            $saledif = $show_date - $query_date;
            $saleDay = round($saledif/(60*60*24));

            if($saleDay > 25){
                $status = 'sale not started';
            }elseif ( $saleDay > 5 && $saleDay < 26){
                $status = 'open for sale';
            }elseif ( $saleDay < 6 && $saleDay >= 0){
                $status = 'sold out';
            }else{
                $status = 'in the past';
            }

            $price = $show['price'];

            if( $saleDay > 25 || $saleDay < 6 ){
                $tickets_available = 0;
            }else{
                $tickets_available = ($runDay < 61) ? 10 : 5;
            }

            // $tickets_available = $saleDay > 25 || $saleDay < 6 ? 0 : ($runDay < 61) ? 10 : 5;
            $tickets_left = ($runDay < 61) ? 200 - ($tickets_available * (25 - $saleDay - 1)) :
                100 - ($tickets_available * (25 - $saleDay - 1));
            $tickets_price = ($runDay < 81) ? $price : 0.8 * $price;

            $response[] =[
                'title' => $show['title'],
                'genre' => $show['genre'],
                'tickets_left' => $tickets_left,
                'tickets_available' => $tickets_available,
                'status' => $status,
                'price' => $tickets_price
            ];

        }

        return $response;
    }

    public function group_by_web($data) {
        $key = 'genre';
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }

    public function group_by_rest($data) {
        $key = 'genre';
        $response = array();
        $inventory = array();

        $genres = array_unique(array_column($data, $key));// get unique genres

        foreach ($genres as $genre ){// for each genre get the records that relate to it

            $shows = array_filter($data, function ($item) use($genre){
                unset($item['price']);
                if($item['genre'] == $genre){
                    return true;
                }
                return false;
            });

            $objects = [];
            // convert array to object
            foreach ($shows as $key => $value){
                $objects[] = $value;
            }
            // remove price from object
            foreach ($objects as &$object){
                unset($object['price']);
            }
            unset($object);

            $response[] = [
              'genre' => $genre,
              'shows' => $objects
            ];

            $objects =[];
        }

        $inventory['inventory'] = $response;


        return $inventory;
    }

}