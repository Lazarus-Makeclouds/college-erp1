<?php
$db = DB::getInstance();
$students = new Students(); 

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
 
    $user = $db->get("students_details", ["Email", "=", $user_email])->results();

    if ($user) {
        $department = $user[0]->Department; 
        $year_of_joining = $user[0]->Joining_year;
        $student_id = $user[0]->id; 
    } else {
        echo "User not found.";
        exit();
    }

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
    
        $attendance_records = [];
        $attendance_records[$student_id] = $students->get_attendance($student_id, $mark_attendance_year, $month); 
    
    ?>

    <ul>
        <a style="text-decoration: none; color:black" href="dashboard.php?src=viewparticularstdattendance">View Attendance</a>
        
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
            <?php if (empty($attendance_records[$student_id])): ?>
                <tr>
                    <td colspan="<?= $days_in_month + 1; ?>">No Attendance Records Found.</td>
                </tr>
            <?php else: ?>
                <tr>
                    <td><?= htmlspecialchars($user[0]->Name); ?></td>
                    <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                        <td>
                            <?php
                            $status = 'N/A'; 
                            foreach ($attendance_records[$student_id] as $attendance) {
                                $attendance_date = (int)date('d', strtotime($attendance->date));
                                $attendance_month = (int)date('m', strtotime($attendance->date));
    
                                if ($attendance_date === $day && $attendance_month == $month) {
                                    $status_code = $attendance->status;
                                    $status = match($status_code) {
                                        '1' => 'Present',
                                        '2' => 'Absent',
                                        '3' => 'Holiday',
                                        default => 'N/A',
                                    };
                                    break;
                                }
                            }
                            echo htmlspecialchars($status);
                            ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="button-container">
            <a href="./dashboard.php?src=viewattendance">
                <button id="back">Back</button>
            </a>
    </div>

<?php
    else:
        echo "Error: No data submitted.";
    endif;

} else {
    echo "Please log in to view your details.";
    exit();
}
?>
