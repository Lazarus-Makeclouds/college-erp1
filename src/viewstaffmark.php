<?php

$db = DB::getInstance();
$student = new Students();

if (!isset($_SESSION['user_email'])) {
    die("You must be logged in to view this page.");
}

$user_email = $_SESSION['user_email'];

$staff_results = $db->get("teachers_details", ["Email", "=", $user_email])->results();

if ($staff_results) {
    $staff = $staff_results[0];
    $teacher_name = $staff->Name; 

    
    $subject_allocations = $db->get("subject_allocation", ["Name", "=", $teacher_name])->results();
} else {
    $subject_allocations = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Internal Marks</title>
</head>

<body>
    <h1 style="text-align: center;">Student Internal Marks</h1>

    <?php
    if (isset($teacher_name)) {
        echo "<p><b><h3>Welcome, $teacher_name!</b></h3></p>";  
    }
    ?>

    <form id="marksForm" action="./dashboard.php?src=viewmarkstaff" method="POST">
        <label for="year">Joining Year:</label>
        <select id="year" name="year" required onclick="fetchDpt()">
            <option value="">--Select Year--</option>
            <?php
            
            if (!empty($subject_allocations)) {
                $years = array_unique(array_column($subject_allocations, 'Year')); 
                foreach ($years as $year) {
                    echo "<option value='$year'>$year</option>";
                }
            }
            
            ?>
        </select>

        <label for="department">Select Department:</label>
        <select id="department" name="department" required>
            <option value="">--Select Department--</option>
            <?php
            
            if (!empty($subject_allocations)) {
                $departments = array_unique(array_column($subject_allocations, 'Department')); // Get unique departments
                foreach ($departments as $department) {
                    echo "<option value='$department'>$department</option>";
                }
            }
            ?>
        </select>

        <label for="sem">Select Semester:</label>
        <select id="sem" name="sem" required>
            <option value="">--Select Semester--</option>
            <option value="Semester-1">Semester-1</option>
            <option value="Semester-2">Semester-2</option>
            <option value="Semester-3">Semester-3</option>
            <option value="Semester-4">Semester-4</option>
            <option value="Semester-5">Semester-5</option>
            <option value="Semester-6">Semester-6</option>
        </select>

        <label for="exam">Exam type :</label>
        <select id="exam" name="exam" required>
            <option value="">--Exam type--</option>
            <option value="1st Internal">1st Internal</option>
            <option value="2nd Internal">2nd Internal</option>
            <option value="3rd Internal">3rd Internal</option>
            <option value="4th Internal">4th Internal</option>
            <option value="5th Internal">5th Internal</option>
            <option value="Model-1">Model-1</option>
            <option value="Model-2">Model-2</option>
            <option value="Model-3">Model-3</option>
            <option value="Model-4">Model-4</option>
            <option value="Model-5">Model-5</option>
        </select>

        <label for="subject">Select Subject:</label>
        <select id="subject" name="subject" required>
            <option value="">--Select Subject--</option>
            <?php
            
            if (!empty($subject_allocations)) {
                $subjects = array_unique(array_column($subject_allocations, 'subject')); // Get unique subjects
                foreach ($subjects as $subject) {
                    echo "<option value='$subject'>$subject</option>";
                }
            }
            ?>
        </select>

        <button type="submit" id="generateStudents">Generate Students</button>
    </form>
</body>
</html>
