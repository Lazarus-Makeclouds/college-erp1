<?php

use function PHPSTORM_META\type;

require_once "./core/init.php";
$db = DB::getInstance();


function getYear($year, $dept)  {
    global $db;
    // $data = [];
    $results = $db->query("SELECT * FROM `batch` WHERE `Year_of_joining` = ? AND `Department` = ?",[$year,$dept]);
    if (!empty($results->results())){
        echo json_encode($results->first());
    }
}


if(Input::exists("get")){
    if(!empty(Input::get_data("dept")) && !empty(Input::get_data("year"))){
        $dept = Input::get("dept");
        $year = Input::get_data("year");
        getYear($year, $dept);
    }
}