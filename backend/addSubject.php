<?php
require_once "./core/init.php";


if (Input::exists()){
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $semester = $_POST['semester'];
    $batch_id = $_POST['batch_id'];
    $credits = $_POST['credits'];

   
    $db = DB::getInstance();
    $data = [
        'batch_id' => $batch_id,
        'subject' => $subject,
        'semester' => $semester,
        'credits' => $credits
    ];

    
    if ($db->insert("batch_subjects", $data)) {
        echo "Subject added successfully!";
    } else {
        echo "Failed to add subject.";
    }
}
}









