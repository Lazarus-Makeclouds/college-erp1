<?php
require_once "./core/init.php";
$db = DB::getInstance();
if (Input::exists('post')) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'Year_of_joining' => array(
            'required' => true
        ),
        'Department' => array(
            'required' => true
        ),
        'Number_of_years' => array(
            'required' => true
        ),
        'Number_of_semesters' => array(
            'required' => true
        )
    ));
    if ($validation->passed()) {
        try {
            $data = array(
                'Year_of_joining' => Input::get('Year_of_joining'),
                'Department' => Input::get('Department'),
                'Number_of_years' => Input::get('Number_of_years'),
                'Number_of_semesters' => Input::get('Number_of_semesters')
            );
            if($db->insert('batch', $data)){
                echo "New batch added successfully";   
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        foreach ($validation->errors() as $error) {
            echo str_replace("_", " ", $error);
        }
    }
}
