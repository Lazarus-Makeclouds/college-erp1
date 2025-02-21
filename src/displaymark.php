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

    // Fetch staff name and subject allocation for the selected year, department, and semester
    $staffAllocationResults = $db->query("SELECT sa.Name AS staffName, sa.Department AS subjectDept, sa.subject, 
               sa.Year, sa.Sem FROM subject_allocation sa 
               WHERE sa.Year = ? AND sa.Department = ? AND sa.Sem = ?", 
        [$year, $department, $sem])->results();

    // Initialize an array to store subject-wise stats
    $subjectStats = [];
?>

<link rel="stylesheet" type="text/css" href="overall.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<ul>
    <a style="text-decoration: none; color:black" href="dashboard.php?src=viewinternalmark">View Internal Marks</a>
    > <a style="text-decoration: none; color:blue" href="#">View All Subject Mark</a> 
</ul>

<!-- Display selection details -->
<table>
    <tr>
        <th>Joining Year</th>
        <th>Select Department</th>
        <th>Select Semester</th>
        <th>Exam Type</th>
    </tr>
    <tbody>
        <tr>
            <td><?= escape($year); ?></td>
            <td><?= escape($department); ?></td>
            <td><?= escape($sem); ?></td>
            <td><?= escape($exam); ?></td>
        </tr>
    </tbody>
</table>

<!-- Display student details and marks with staff information -->
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
        <tr>
            <th>Staff Name</th>
            <th>Department</th>
            <th>Subject</th>
            <th>Max Marks</th>
            <th>Min Marks</th>
            <th>No. of Pass</th>
            <th>No. of Fail</th>
            <th>No. of Absent</th>
            <th>No. of Present</th>
            <th>Pass %</th>
            <th>Subject Average</th>
            <th>Graph</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($staffAllocationResults as $allocation): ?>
            <tr>
                <td><?= escape($allocation->staffName); ?></td>
                <td><?= escape($allocation->subjectDept); ?></td>
                <td><?= escape($allocation->subject); ?></td>

                <?php
                // For each subject, fetch the max and min marks and the pass/fail stats
                if (isset($subjectStats[$allocation->subject])): 
                    $stats = $subjectStats[$allocation->subject];
                    ?>
                    <td><?= $stats['maxMarks']; ?></td>
                    <td><?= $stats['minMarks']; ?></td>
                    <td><?= $stats['pass']; ?></td>
                    <td><?= $stats['fail']; ?></td>
                    <td><?= $stats['absent']; ?></td>
                    <td><?= $stats['totalStudents'] - $stats['absent']; ?></td>
                    <td><?= $stats['totalStudents'] > 0 ? round(($stats['pass'] / $stats['totalStudents']) * 100, 2) . '%' : '0%'; ?></td>
                    <td><?= $stats['totalStudents'] > 0 ? round($stats['totalMarks'] / $stats['totalStudents'], 2) : '0'; ?></td>
                    <td><canvas id="chart_<?= escape($allocation->subject); ?>" width="200" height="200"></canvas></td>
                <?php else: ?>
                    <td colspan="10">No data available</td>
                <?php endif; ?>
            </tr>

            <script>
                // Chart.js setup for each subject
                var ctx = document.getElementById('chart_<?= escape($allocation->subject); ?>').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar', // Bar chart
                    data: {
                        labels: ['Pass', 'Fail', 'Absent'],
                        datasets: [{
                            label: 'Subject: <?= escape($allocation->subject); ?>',
                            data: [
                                <?= $stats['pass']; ?>, 
                                <?= $stats['fail']; ?>, 
                                <?= $stats['absent']; ?>
                            ],
                            backgroundColor: ['#4CAF50', '#F44336', '#FFEB3B'],
                            borderColor: ['#4CAF50', '#F44336', '#FFEB3B'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
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
    // Download CSV
    document.getElementById('downloadCSV').addEventListener('click', function() {
        let csvContent = '';
        const rows = document.querySelectorAll('table tr');
        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const rowData = Array.from(cols).map(col => col.innerText.replace(/,/g, ''));
            csvContent += rowData.join(',') + '\n';
        });
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'students.csv';
        a.click();
    });

    // Download Excel
    document.getElementById('downloadExcel').addEventListener('click', function() {
        const table = document.querySelector('table');
        const workbook = XLSX.utils.table_to_book(table);
        XLSX.writeFile(workbook, 'students.xlsx');
    });
</script>
