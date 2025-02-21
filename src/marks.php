<?php
if (isset($_GET["sem"])) {
    $db = DB::getInstance();
    $student = new Students();
    $sem = Input::get("sem");
    $id = Input::get("id");
    $details = $db->get("students_details", ["id", "=", $id])->results();

    if (!empty($details)) {
        $department = $details[0]->Department;
        $yoj = $details[0]->Joining_year;
        $batch_id = $student->get_batch_id($yoj, $department);
        $exams = $db->query("SELECT DISTINCT(exam) FROM internal_marks WHERE batch_id = ? ORDER BY exam", [$batch_id])->results();
?>
        <ul>
            <a style="text-decoration: none; color:black" href="dashboard.php?src=managestudents">Manage Students</a>
            <?php if (isset($_GET['id'])): ?>
                > <a style="text-decoration: none; color:black" href="dashboard.php?src=individualstudents&id=<?= $_GET['id']; ?>">Student Profile</a>
            <?php endif; ?>
            <?php if (isset($_GET['sem'])): ?>
                > <a  style="text-decoration: none; color:blue" href="hp-works/dashboard.php?src=marks&sem=<?= $_GET['sem']; ?>&id=<?= $_GET['id']; ?>">Marks - Semester <?= $_GET['sem']; ?></a>
            <?php endif; ?>
        </ul>

        <script>
            function downloadCSV(tableId) {
                const table = document.getElementById(tableId);
                let csvContent = "";

                for (let row of table.rows) {
                    let rowData = [];
                    for (let cell of row.cells) {
                        rowData.push(cell.innerText);
                    }
                    csvContent += rowData.join(",") + "\n";
                }

                const blob = new Blob([csvContent], { type: 'text/csv' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `${tableId}_data.csv`;
                link.click();
            }

            function downloadExcel(tableId) {
                const table = document.getElementById(tableId);
                const worksheet = XLSX.utils.table_to_sheet(table);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, tableId);
                XLSX.writeFile(workbook, `${tableId}_data.xlsx`);
            }

            async function downloadPDF(tableId) {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                doc.autoTable({
                    html: `#${tableId}`,
                    startY: 20,
                    theme: 'grid',
                    headStyles: { fillColor: [41, 128, 185] }
                });
                doc.save(`${tableId}_data.pdf`);
            }
        </script>

        <?php
        foreach ($exams as $exam):
            $subjectMarks = [];
            $hasMarks = false;
            $subject_id = $student->get_subject_id($batch_id, $sem);

            foreach ($subject_id as $sub_id):
                $finals = $student->get_marks($batch_id, $sub_id->id, $id, $exam->exam);
                if (!empty($finals)):
                    $hasMarks = true;
                    foreach ($finals as $final):
                        $subjectMarks[$student->get_subjectmark($final->subject_id)] = $final->marks;
                    endforeach;
                endif;
            endforeach;

            if ($hasMarks): 
        ?>
            <h2><?= $exam->exam; ?></h2>
            <div class="button-container">
                <button onclick="downloadCSV('<?= $exam->exam; ?>')">Download CSV</button>
                <button onclick="downloadExcel('<?= $exam->exam; ?>')">Download Excel</button>
            </div>
            <table id="<?= $exam->exam; ?>">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjectMarks as $subject => $marks): ?>
                        <tr>
                            <td><?= $subject ?></td>
                            <td><?= $marks ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php
            endif;
        endforeach;
        ?>

        <!-- Semester Marks Section -->
        <?php
        $sem_subjects = $db->query("SELECT `subject`, `id` FROM batch_subjects WHERE `batch_id` = ? AND `semester` = ?", [$batch_id, $sem])->results();
        $hasMarks = false;
        ?>
        <h2>Semester Marks</h2>
        <div class="button-container">
            <button onclick="downloadCSV('semmarks')">Download CSV</button>
            <button onclick="downloadExcel('semmarks')">Download Excel</button>
        </div>
        <table id="semmarks">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sem_subjects as $sem_subject):
                    $finalsem = $student->get_marks($batch_id, $sem_subject->id, $id);
                    if (!empty($finalsem)):
                        $hasMarks = true;
                        foreach ($finalsem as $final): ?>
                            <tr>
                                <td><?= $student->get_subjectmark($sem_subject->id) ?></td>
                                <td><?= $final->marks ?></td>
                            </tr>
                        <?php endforeach;
                    endif;
                endforeach;
                ?>
            </tbody>
        </table>

<?php
    } else {
        echo "Student not found!";
    }
} else {
    Redirect::to("./dashboard.php");
}
?>
