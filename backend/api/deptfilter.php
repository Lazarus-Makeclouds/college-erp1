<?php 
require_once "./core/init.php";
$db = DB::getInstance();
$students = new Students();

function getdept($year){
    $departments=[];
    global $db;
    $results = $db->get("batch",["Year_of_joining","=",$year])->results();
    foreach ($results as $result){
        $departments[] = $result->Department;
    }
    echo json_encode($departments);
}

function getSem($dept, $year){
    global $db;
    $sem = [];
    $results = $db->query("SELECT * FROM `batch` WHERE `Year_of_joining` = ? AND `Department` = ? ;",[$year,$dept])->results();
    if (!empty($results)){
        foreach ($results as $result){ 
            $sem[] = $result->Number_of_semesters;
        }
        echo json_encode($sem);
    }
}

function getSub($dept, $year, $sem){
    global $students;
    $subjects = [];
    $results = $students->get_subjects($year, $dept, $sem);
    if (!empty($results)){
        foreach ($results as $result){ 
            $subjects[] = $result->subject;
        }
        echo json_encode($subjects);
    }
}

if (Input::exists("get")){
    if(!empty(Input::get_data("dept")) && !empty(Input::get_data("year")) && !empty(Input::get_data("sem"))){
        $dept = Input::get("dept");
        $year = Input::get_data("year");
        $sem = Input::get_data("sem");
        getSub($dept, $year, $sem);
    }elseif (!empty(Input::get_data("dept")) && !empty(Input::get_data("year"))) {
        $dept = Input::get("dept");
        $year = Input::get_data("year");
        getSem($dept, $year);
    } elseif(!empty(Input::get_data("year"))) {
        $year = Input::get("year");
        getdept($year);
    }
}