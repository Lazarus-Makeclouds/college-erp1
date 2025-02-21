<?php
require_once '../core/init.php'; 
$db = DB::getInstance();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = Input::get('student_id');
    $batch_id = Input::get('batch_id');
    $attendance = Input::get('attendance'); 
    $date = Input::get('date');

    // Validate the input
    if (empty($student_id) || empty($batch_id) || !isset($attendance) || empty($date)) {
        echo "Error: Missing required fields.";
        exit;
    }
    $db = DB::getInstance();
    $existingRecord = $db->query("SELECT * FROM attendance WHERE student_id = ? AND date = ?", [$student_id, $date])->results();

    if (count($existingRecord) > 0) {
        // Update the existing record
        $db->update('attendance', $existingRecord[0]->id, [
            'attendance' => $attendance
        ]);
        echo "Attendance updated successfully.";
    } else {
        // Insert a new record
        $db->insert('attendance', [
            'batch_id' => $batch_id,
            'student_id' => $student_id,
            'date' => $date,
            'attendance' => $attendance
        ]);
        echo "Attendance recorded successfully.";
    }
} else {
    echo "Error: Invalid request method.";
}
?>