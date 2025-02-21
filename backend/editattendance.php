<?php
require_once "./core/init.php"; 
$db = DB::getInstance(); 

if (Input::exists('post')) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'student_id' => array(
            'required' => true,
            'numeric' => true
        ),
        'batch_id' => array(
            'required' => true,
            'numeric' => true
        ),
        'date' => array(
            'required' => true
        ),
        'status' => array(
            'required' => true
        )

    ));
    if ($validation->passed()) {
        try {
            
            $student_id = Input::get('student_id');
            $batch_id = Input::get('batch_id');
            $date = Input::get('date');
            $status = Input::get('status');
            
            

            if ($result) {
                echo "attendance updated successfully.";
            } else {
                echo "Error: Could not update marks.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        
        foreach ($validation->errors() as $error) {
            echo str_replace("_", " ", $error) . "<br>";
        }
    }
} else {
    echo "Error: Invalid request method.";
}
?>
