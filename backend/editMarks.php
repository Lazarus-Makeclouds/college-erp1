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
        'subject_id' => array(
            'required' => true,
            'numeric' => true
        ),
        'batch_id' => array(
            'required' => true,
            'numeric' => true
        ),
        'mark' => array(
            'required' => true,
            'numeric' => true
        ),
        'exam' => array(
            'required' => true
        )
    ));

    if ($validation->passed()) {
        try {
            
            $data = array(
                "marks" => Input::get('mark')
            );

            
            $student_id = Input::get('student_id');
            $subject_id = Input::get('subject_id');
            $batch_id = Input::get('batch_id');
            $exam = Input::get('exam');

            
            $updateQuery = "UPDATE internal_marks SET marks = ? WHERE student_id = ? AND subject_id = ? AND batch_id = ? AND exam = ?";
            $result = $db->query($updateQuery, [$data['marks'], $student_id, $subject_id, $batch_id, $exam]);

            if ($result) {
                echo "Marks updated successfully.";
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