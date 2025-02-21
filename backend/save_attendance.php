<?php
require_once "./core/init.php";

if (Input::exists()) {
    $db = DB::getInstance();
    $response = []; // Array to store responses
    try {
        // Get the student data from the form submission
        $studentIds = Input::get('student_id');
        $batchIds = Input::get('batch_id');
        $statuses = Input::get('status');
        $date = Input::get('date'); // Only a single date is expected

        // Loop through the student data and save each one
        foreach ($studentIds as $index => $studentId) {
            $data = [
                "batch_id" => $batchIds[$index],
                "date" => $date,
                "student_id" => $studentId,
                "status" => $statuses[$index],
            ];

            if ($db->insert("attendance", $data)) {
                $response[] = "Attendance added for student ID: $studentId";
            } else {
                $response[] = "Error adding attendance for student ID: $studentId";
            }
        }

        // Return a unified JSON response
        echo json_encode([
            "message" => "Attendance processed successfully!",
            "details" => $response
        ]);
    } catch (Exception $e) {
        // Return error response
        echo json_encode(["message" => "Error: " . $e->getMessage()]);
    }
}
?>

