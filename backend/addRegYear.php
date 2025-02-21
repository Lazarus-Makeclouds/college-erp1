<?php 
require_once "./core/init.php";
$db = DB::getInstance();
if(Input::exists()){
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'year' => [
            "required" => true,
        ],
        'regulation' => [
            "required" => true,
            
        ]
    ]);
    if ($validation->passed()){
        try {
            $data = array(
                "year" => Input::get("year"),
                "regulation" => Input::get("regulation")
            );
            $db->insert("regulationyear", $data);
            echo "Regulation year added successfully!";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        echo "Regulation year already exists!";
    }
}