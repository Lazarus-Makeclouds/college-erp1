<?php
require_once "./core/init.php";

if (Input::exists()) {
    $db = DB::getInstance();
    try {
        $marks = Input::get("marks"); // Get the array of marks

        foreach ($marks as $markData) {
            $data = json_decode($markData, true); // Decode the JSON data

            $dataToInsert = [
                "batch_id" => $data['batch_id'],
                "subject_id" => $data['subject_id'],
                "student_id" => $data['student_id'],
                "marks" => $data['mark'],
            ];

            if (!$db->insert("semester_marks", $dataToInsert)) {
                throw new Exception("Failed to add marks for student ID: " . $data['student_id']);
            }
        }

        echo "Marks added successfully!";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


