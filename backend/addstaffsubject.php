<?php
require_once "./core/init.php";
$db = DB::getInstance();

if (Input::exists()) {
    // Get input values
    $teacher_id = Input::get('teacher_id');
    $year = Input::get('year');
    $department = Input::get('department');
    $semester = Input::get('sem');
    $subject = Input::get('subject');

    // Fetch subject_id from batch_subject table based on subject
    $query = "SELECT id FROM batch_subjects WHERE subject = ?";
    $params = [$subject];
    $subject_result = $db->query($query, $params)->first();  // Get the first result

    // Check if subject_id was found
    if ($subject_result) {
        $subject_id = $subject_result->id;
    } else {
        echo "Subject not found in batch_subject table.";
        exit;  // Exit if subject_id is not found
    }

    // Prepare data for insertion, including subject_id
    $data = array(
        "Name" => $teacher_id,
        "year" => $year,
        "department" => $department,
        "Sem" => $semester,
        "subject" => $subject,
        "subject_id" => $subject_id,  // Add subject_id here
    );

    try {
        // Check if the subject has already been allocated in the selected year, department, and semester
        $query = "SELECT COUNT(*) AS count FROM subject_allocation WHERE year = ? AND department = ? AND Sem = ? AND subject = ?";
        $params = [$year, $department, $semester, $subject];
        $result = $db->query($query, $params)->first();  // Execute query and get first result

        // If count is greater than 0, it means subject has already been allocated to another teacher
        if ($result->count > 0) {
            echo "This subject has already been allocated to a teacher for the selected year, department, and semester.";
        } else {
            // If the subject has not been allocated yet, proceed with insertion
            if ($db->insert("subject_allocation", $data)) {
                echo "Subject allocated successfully!";
            } else {
                echo "Failed to allocate subject.";
            }
        }
    } catch (Exception $e) {
        // Handle any exceptions
        echo "Error: " . $e->getMessage();
    }
}
?>
