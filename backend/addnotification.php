<?php
require_once "./core/init.php";
$db = DB::getInstance();

if (Input::exists('post')) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'notification-text' => array(
            'required' => true
        )
    ));
    if ($validation->passed()) {
        try {
            $data = array(
        "Notification" => Input::get('notification-text')
            );
            if($db->insert('notifications', $data)){
                echo "notification added successfully";   
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

?>

