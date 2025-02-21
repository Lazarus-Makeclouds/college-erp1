<?php
$db = DB::getInstance();
$students = new Students(); 

if (Input::exists("post")):
    $year = htmlspecialchars(Input::get("Joining_year"));           
    $department = htmlspecialchars(Input::get("Department"));
    $sem = htmlspecialchars(Input::get("sem"));                      
    $month = (int) htmlspecialchars(Input::get("monthPicker"));       
    $mark_attendance_year = htmlspecialchars(Input::get("Number_of_years")); 
    
    if ($month < 1 || $month > 12) {
        die("Error: Invalid month provided.");
    }

    
    if (!is_numeric($mark_attendance_year) || (int)$mark_attendance_year <= 0) {
        die("Error: Invalid Mark Attendance Year.");
    }

    $mark_attendance_year = (int)$mark_attendance_year; 

    
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $mark_attendance_year);

    
    $results = $db->query(
        "SELECT * FROM students_details WHERE YEAR(Joining_year) = ? AND Department = ? ORDER BY Department ASC",
        [$year, $department]
    )->results();

    
    $attendance_records = [];
    foreach ($results as $student) {
        $student_id = $student->id;
        
        $attendance_records[$student_id] = $students->get_attendance($student_id, $mark_attendance_year, $month); 
    }
?>

<ul>
    <a  style="text-decoration: none; color:black" href="dashboard.php?src=viewattendance">View Attendance</a>
    
        > <a style="text-decoration: none; color:blue" href="#">View MonthWise Attendance</a> 
</ul>
<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                <th><?= $day; ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody id="studentMarks">
        <?php if (empty($results)): ?>
            <tr>
                <td colspan="<?= $days_in_month + 1; ?>">No Students Found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($results as $student): ?>
                <?php
                $student_id = $student->id;
                $student_name = htmlspecialchars($student->Name); // Get student name
                ?>
                <tr>
                    <td><?= $student_name; ?></td>
                    <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                        <td>
                            <?php
                            $status = 'N/A'; // Default status
                            if (!empty($attendance_records[$student_id])) {
                                foreach ($attendance_records[$student_id] as $attendance) {
                                    // Check if the attendance date matches the current day and month
                                    $attendance_date = (int)date('d', strtotime($attendance->date));
                                    $attendance_month = (int)date('m', strtotime($attendance->date));

                                    if ($attendance_date === $day && $attendance_month == $month) {
                                        $status_code = $attendance->status;
                                        // Map status codes to meaningful labels
                                        $status = match($status_code) {
                                            '1' => 'Present',
                                            '2' => 'Absent',
                                            '3' => 'Holiday',
                                            default => 'N/A',
                                        };
                                        break; // Exit loop once we find the status for the day
                                    }
                                }
                            }
                            echo htmlspecialchars($status);
                            ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
    
</table>
<div class="button-container">
        <a href="./dashboard.php?src=viewattendance">
            <button id="back">Back</button>
        </a>
    </div>


<?php else: ?>
    <p>Error: No data submitted.</p>
<?php endif; ?>
