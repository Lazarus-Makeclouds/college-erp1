<?php
require_once "./core/init.php";
$db = DB::getInstance();

if (Input::exists()) {
    $db = DB::getInstance();
    $data = array(
        "from" => Input::get('from'),               
        "to" => Input::get('to'),                   
        "year" => Input::get('year'),               
        "department" => Input::get('department'),   
        "semester" => Input::get('sem'),            
        "Image" => Input::image_resize(1000, 1000, Input::get('fileInput', 'tmp_name'), Input::get('fileInput', 'type'), Input::get('fileInput', 'size')),
        "filetype" => Input::get('fileInput', 'type')
    );
   
    try{
        if($db->insert("timetable",$data)){
            echo "Timetable added successfully";
        } else {
            echo "Failed to add Timetable";
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }

}
