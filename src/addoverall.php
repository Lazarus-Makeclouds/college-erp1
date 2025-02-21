<?php
$students = new Students();
$db = DB::getInstance();
$results = $db->get_all("students_details", "Name")->results();

if (Input::exists("post")):
    $year = Input::get("year");
    $department = Input::get("department");
    $sem = Input::get("sem");
    $exam = Input::get("exam");

    // Fetch all subjects for the selected year, department, and semester
    $batch_id = $students->get_batch_id($year, $department);
    $subject_id_arrays = $students->get_subject_id($batch_id, $sem);

    // Fetch all students for the selected year and department
    $results = $db->query("SELECT * FROM `students_details` WHERE YEAR(`Joining_year`) = ? AND `Department` = ? ORDER BY `Department` ASC", [$year, $department])->results();

    // Initialize an array to store subject-wise stats
    $subjectStats = [];
?>

    <link rel="stylesheet" type="text/css" href="overall.css" />
    <ul>
    <a  style="text-decoration: none; color:black" href="dashboard.php?src=overall">View Internal Marks</a>
    
        > <a style="text-decoration: none; color:blue" href="#">View All Subject Mark</a> 
</ul>

    <!-- Display selection details -->
    <table>
        
        <thead>
            <tr>
                <th>Joining Year</th>
                <th>Select Department</th>
                <th>Select Semester</th>
                <th>Exam Type</th>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <td><?= escape($year); ?></td>
                <td><?= escape($department); ?></td>
                <td><?= escape($sem); ?></td>
                <td><?= escape($exam); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Display student details and marks -->
    <table>
    <button id="downloadCSV">Download CSV</button>
    <button id="downloadExcel">Download Excel</button>
        <thead>
            <tr>
                <th>Register Number</th>
                <th>Student Name</th>
                <?php foreach ($subject_id_arrays as $subject_id_array): ?>
                    <th><?= escape($subject_id_array->subject); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody id="studentMarks">
            <?php if (empty($results)): ?>
                <tr>
                    <td colspan="<?= count($subject_id_arrays) + 3; ?>">No Students Found.</td>
                </tr>
                <?php else:
                $i = 1;
                foreach ($results as $student):
                ?>
                    <tr>
                        <td><?= escape($student->Register_number); ?></td>
                        <td><?= escape($student->Name); ?></td>
                        <?php foreach ($subject_id_arrays as $subject_id_array):
                            $marks = $students->get_marks($batch_id, $subject_id_array->id, $student->id, $exam);
                            $mark = !empty($marks) ? $marks[0]->marks : 'A';
                            $markClass = '';

                            // Initialize stats for the subject if not already initialized
                            if (!isset($subjectStats[$subject_id_array->subject])) {
                                $subjectStats[$subject_id_array->subject] = [
                                    'pass' => 0,
                                    'fail' => 0,
                                    'absent' => 0,
                                    'totalMarks' => 0,
                                    'totalStudents' => 0,
                                    'maxMarks' => -INF,
                                    'minMarks' => INF
                                ];
                            }

                            // Update the subject statistics
                            if ($mark == 'A') {
                                $markClass = 'absent';
                                $subjectStats[$subject_id_array->subject]['absent']++;
                            } elseif ($mark < 24) {
                                $markClass = 'fail';
                                $subjectStats[$subject_id_array->subject]['fail']++;
                            } else {
                                $markClass = 'pass';
                                $subjectStats[$subject_id_array->subject]['pass']++;
                            }

                            if ($mark != 'A') {
                                $subjectStats[$subject_id_array->subject]['totalMarks'] += $mark;
                                $subjectStats[$subject_id_array->subject]['totalStudents']++;
                            }

                            // Track max and min marks
                            if ($mark != 'A') {
                                $subjectStats[$subject_id_array->subject]['maxMarks'] = max($subjectStats[$subject_id_array->subject]['maxMarks'], $mark);
                                $subjectStats[$subject_id_array->subject]['minMarks'] = min($subjectStats[$subject_id_array->subject]['minMarks'], $mark);
                            }
                        ?>
                            <td class="<?= $markClass; ?>"><?= escape($mark); ?></td>
                        <?php endforeach; ?>
                    </tr>
            <?php
                    $i++;
                endforeach;
            endif;
            ?>
           

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


            <?php foreach ($subjectStats as $subject => $stats): ?>
                <tr>
                    <td><?= escape($subject); ?></td>
                    <td><?= $stats['maxMarks']; ?></td>
                    <td><?= $stats['minMarks']; ?></td>
                    <td><?= $stats['pass']; ?></td>
                    <td><?= $stats['fail']; ?></td>
                    <td><?= $stats['absent']; ?></td>
                    <td><?= $stats['totalStudents'] - $stats['absent']; ?></td>
                    <td><?= $stats['totalStudents'] > 0 ? round(($stats['pass'] / $stats['totalStudents']) * 100, 2) . '%' : '0%'; ?></td>
                    <td><?= $stats['totalStudents'] > 0 ? round($stats['totalMarks'] / $stats['totalStudents'], 2) : '0'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    clearstatcache();
    ?>
<?php else: ?>
    <p>Error: No data submitted.</p>
<?php endif; ?>
<div class="button-container">
        <a href="./dashboard.php?src=overall">
            <button>Back</button>
        </a>
    </div>
<script>
    function editMarks(index) {
        const form = document.getElementById("editMarks" + index);
        const student_id = form.querySelector('input[name="student_id"]').value;
        const batch_id = form.querySelector('input[name="batch_id"]').value;
        const exam = form.querySelector('input[name="exam"]').value;
        const currentMarks = form.parentElement.parentElement.querySelectorAll('td:nth-child(n+3):nth-child(-n+<?= count($subject_id_arrays); ?>)');

        let newMarks = [];
        currentMarks.forEach((cell, idx) => {
            const subject = cell.previousElementSibling.textContent;
            const currentMark = cell.textContent;
            const newMark = prompt(`Enter the new mark for ${subject} (current mark: ${currentMark}):`, currentMark);
            newMarks.push({
                subject,
                newMark
            });
        });

        if (newMarks.length > 0) {
            const formData = new URLSearchParams();
            formData.append("student_id", student_id);
            formData.append("batch_id", batch_id);
            formData.append("exam", exam);

            newMarks.forEach(mark => {
                formData.append(mark.subject, mark.newMark);
            });

            fetch("backend/editMarks.php", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData.toString()
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
    }
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