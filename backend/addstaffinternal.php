<?php
require_once "./core/init.php"; // Ensure this path is correct

// Start the session if it's not started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_email'])) {
    die("You must be logged in to view this page.");
}

$user_email = $_SESSION['user_email'];
$db = DB::getInstance();

// Fetch teacher's details based on email
$staff_results = $db->get("teachers_details", ["Email", "=", $user_email])->results();

if ($staff_results) {
    $staff = $staff_results[0];
    $teacher_name = $staff->Name;
    $staff_id = $staff->id; // Assuming 'id' is the field storing staff ID
} else {
    die("Teacher not found.");
}

var_dump($teacher_name); // For debugging purposes

// Check if form data exists
if (Input::exists()) {
    try {
        // Validate and sanitize the inputs
        $batch_id = Input::get("batch_id");
        $subject_id = Input::get("subject_id");
        $student_id = Input::get("student_id");
        $mark = Input::get("mark");
        $exam = Input::get("exam");

        
        // Prepare the data for insertion
        $data = [
            "staff_id" => $staff_id, // Using staff_id fetched from the teacher's data
            "batch_id" => $batch_id,
            "subject_id" => $subject_id,
            "student_id" => $student_id,
            "mark" => $mark,
            "exam" => $exam
        ];

        // Insert the data into the staffmarkadd table
        if ($db->insert("staffmarkadd", $data)) {
            echo "Marks added successfully!";
        } else {
            echo "Error: Could not add marks.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: No data submitted.";
}
?>
