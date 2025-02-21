<?php
require_once "./core/init.php";

if (Input::exists()) {
    $db = DB::getInstance();
    try {
        // Get the arrays from the input
        $batch_ids = Input::get("batch_id");
        $subject_ids = Input::get("subject_id");
        $student_ids = Input::get("student_id");
        $marks = Input::get("mark");
        $exams = Input::get("exam");

        // Loop through each student and insert their marks
        for ($i = 0; $i < count($student_ids); $i++) {
            $data = [
                "batch_id" => $batch_ids[$i],
                "subject_id" => $subject_ids[$i],
                "student_id" => $student_ids[$i],
                "marks" => $marks[$i],
                "exam" => $exams[$i]
            ];

            // Insert each student's marks into the database
            if (!$db->insert("internal_marks", $data)) {
                throw new Exception("Failed to add marks for student ID: " . $student_ids[$i]);
            }
        }

        echo "Marks added successfully!";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}