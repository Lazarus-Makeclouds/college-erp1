<?php
$students = new Students();
$db = DB::getInstance();
$results = $db->get_all("students_details", "Name")->results();

if (Input::exists("post")):
    $year = Input::get("year");
    $department = Input::get("department");
    $sem = Input::get("sem");
    $exam = Input::get("exam");

    // Fetch students based on year and department
    $results = $db->query("SELECT * FROM `students_details` WHERE YEAR(`Joining_year`) = ? AND `Department` = ? ORDER BY `Department` ASC", [$year, $department])->results();

    // Fetch all subjects for the selected batch and semester
    $batch_id = $students->get_batch_id($year, $department);
    $subject_id_arrays = $students->get_subject_id($batch_id, $sem);

    // Prepare a list of subjects for the table header
    $subjects = [];
    foreach ($subject_id_arrays as $subject_id_array) {
        $subjects[] = $subject_id_array;
    }

    // Initialize an array to store the summary statistics for each subject
    $subject_stats = [];

    foreach ($subjects as $subject) {
        // Initialize stats for each subject
        $subject_stats[$subject->subject] = [
            'max' => -1,
            'min' => PHP_INT_MAX,
            'pass_count' => 0,
            'fail_count' => 0,
            'absent_count' => 0,
            'present_count' => 0,
            'total_marks' => 0,
            'students' => 0
        ];
    }

?>
<ul>
    <a  style="text-decoration: none; color:black" href="dashboard.php?src=OverallMark">View OverAll Marks</a>
    
        > <a style="text-decoration: none; color:blue" href="#">View OverAll Mark & Average For Each Subject</a> 
</ul>
    <table>

        <thead>
            <tr>
                <th>Joining Year</th>
                <th>Select Department</th>
                <th>Select Semester</th>

            </tr>
        </thead>

        <tbody>
            <tr>
                <td><?= escape($year); ?></td>
                <td><?= escape($department); ?></td>
                <td><?= escape($sem); ?></td>

            </tr>
        </tbody>
    </table>
    <!-- Displaying student and marks information -->
    <table>
        <button id="downloadCSV">Download CSV</button>
        <button id="downloadExcel">Download Excel</button>
        <thead>
            <tr>
                <th>Register Number</th>
                <th>Student Name</th>
                <?php foreach ($subjects as $subject): ?>
                    <th><?= escape($subject->subject); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody id="studentMarks">
            <?php if (empty($results)): ?>
                <tr>
                    <td colspan="5">No Students Found.</td>
                </tr>
            <?php else:
                foreach ($results as $student):
                    echo "<tr>";
                    echo "<td>" . escape($student->Register_number) . "</td>";
                    echo "<td>" . escape($student->Name) . "</td>";

                    foreach ($subjects as $subject) {
                        $marks = $students->get_marks($batch_id, $subject->id, $student->id);
                        $mark = !empty($marks) ? $marks[0]->marks : 'A'; // Default to 'A' if no mark


                        // Determine the color for the mark
                        $markColor = '';
                        if ($mark === 'A') {
                            $markColor = 'gray'; // Absent
                        } elseif ($mark >= 35) {
                            $markColor = 'green'; // Pass
                        } else {
                            $markColor = 'red'; // Fail
                        }
                        // Update subject statistics
                        if ($mark !== 'A') {
                            $subject_stats[$subject->subject]['present_count']++;
                            $subject_stats[$subject->subject]['total_marks'] += $mark;

                            // Check for pass/fail
                            if ($mark >= 35) {
                                $subject_stats[$subject->subject]['pass_count']++;
                            } else {
                                $subject_stats[$subject->subject]['fail_count']++;
                            }

                            // Update max and min marks
                            $subject_stats[$subject->subject]['max'] = max($subject_stats[$subject->subject]['max'], $mark);
                            $subject_stats[$subject->subject]['min'] = min($subject_stats[$subject->subject]['min'], $mark);
                        } else {
                            $subject_stats[$subject->subject]['absent_count']++;
                        }

                        // Display the mark for each subject with color
                        echo "<td style='color: $markColor;'>" . escape($mark) . "</td>";
                    }

                    echo "</tr>";
                endforeach;
            endif; ?>

            <!-- Subject Summary (calculated based on present students) -->
            <tr>

                <th>Subject</th>
                <th>Max Marks</th>
                <th>Min Marks</th>
                <th>No. of Pass</th>
                <th>No. of Fail</th>
                <th>No. of Absent</th>
                <th>No. of Present</th>
                <th>Pass %</th>
                <th>Subject Average</th>
            </tr>


            <?php foreach ($subject_stats as $subject_name => $stats): ?>
                <tr>
                    <td><?= escape($subject_name); ?></td>
                    <td><?= ($stats['max'] == -1) ? 'N/A' : escape($stats['max']); ?></td>
                    <td><?= ($stats['min'] == PHP_INT_MAX) ? 'N/A' : escape($stats['min']); ?></td>
                    <td><?= escape($stats['pass_count']); ?></td>
                    <td><?= escape($stats['fail_count']); ?></td>
                    <td><?= escape($stats['absent_count']); ?></td>
                    <td><?= escape($stats['present_count']); ?></td>
                    <td><?= ($stats['present_count'] > 0) ? round(($stats['pass_count'] / $stats['present_count']) * 100, 2) . '%' : '0%'; ?></td>
                    <td><?= ($stats['present_count'] > 0) ? round($stats['total_marks'] / $stats['present_count'], 2) : '0'; ?></td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <div class="button-container">
        <a href="./dashboard.php?src=OverallMark">
            <button>Back</button>
        </a>
    </div>

    <?php clearstatcache(); ?>

<?php
else:
    echo "Error: No data submitted.";
endif;
?>
<script>
    // Download CSV
    document.getElementById('downloadCSV').addEventListener('click', function() {
        let csvContent = '';
        const rows = document.querySelectorAll('#studentMarks tr');
        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const rowData = Array.from(cols).map(col => col.innerText.replace(/,/g, ''));
            csvContent += rowData.join(',') + '\n';
        });
        const blob = new Blob([csvContent], {
            type: 'text/csv'
        });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'students.csv';
        a.click();
    });

    // Download Excel
    document.getElementById('downloadExcel').addEventListener('click', function() {
        const table = document.getElementById('studentMarks');
        const workbook = XLSX.utils.table_to_book(table);
        XLSX.writeFile(workbook, 'students.xlsx');
    });
</script>