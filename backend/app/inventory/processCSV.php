<?php

namespace App\inventory;


class processCSV {

    public function returnJson($csv){

        $response =[];

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
            
        }

        return $response;

    }

}