<?php
// Fetch staff ID, year, department, and semester from the GET request
$staffId = $_GET['staffId'];
$year = $_GET['year'];
$dept = $_GET['dept'];
$sem = $_GET['sem'];

// Assuming you're using a Staff model or direct database query
$staffSubjects = $db->query("
    SELECT subjects.subject_id, subjects.subject_name 
    FROM staff_subjects
    INNER JOIN subjects ON staff_subjects.subject_id = subjects.id
    WHERE staff_subjects.staff_id = ? AND staff_subjects.year = ? AND staff_subjects.department = ? AND staff_subjects.semester = ?
", [$staffId, $year, $dept, $sem])->results();

// Return the subjects assigned to the staff member
echo json_encode($staffSubjects);
?>