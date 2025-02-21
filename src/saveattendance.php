<?php
require_once "./core/init.php";

if (Input::exists()) {
    $db = DB::getInstance();

    try {
        // Fetch input values
        $class_date = Input::get("class_date");
        $batch_id = Input::get("batch_id");
        $student_ids = Input::get("student_id"); // Array of student IDs
        $statuses = Input::get("status");       // Array of statuses corresponding to students

        if (!$class_date || !$batch_id || empty($student_ids)) {
            echo "Missing required data. Please fill out all fields.";
            exit;
        }

        // Start a transaction
        $db->beginTransaction();

        // Delete existing attendance for the given date and batch
        $db->delete("attendance", [
            "date" => $class_date,
            "batch_id" => $batch_id,
        ]);

        // Insert new attendance records
        foreach ($student_ids as $index => $student_id) {
            $status = $statuses[$index] ?? null;

            if ($status) {
                $data = [
                    "student_id" => $student_id,
                    "date" => $class_date,
                    "batch_id" => $batch_id,
                    "status" => $status,
                ];

                if (!$db->insert("attendance", $data)) {
                    throw new Exception("Failed to save attendance for student ID: $student_id");
                }
            }
        }

        // Commit the transaction
        $db->commit();
        echo "Attendance saved successfully!";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $db->rollBack();
        echo "An error occurred: " . $e->getMessage();
    }
}
