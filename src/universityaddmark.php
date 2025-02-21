<?php

$students = new Students();
$db = DB::getInstance();
$results = $db->get_all("students_details", "Name")->results();
if (Input::exists("post")):
    $year = Input::get("year");
    $department = Input::get("department");
    $sem = Input::get("sem");
    $subject = Input::get("subject");
    $exam = Input::get("exam");

    $results = $db->query("SELECT * FROM `students_details` WHERE YEAR(`Joining_year`) = ? AND `Department` = ? ORDER BY `Department` ASC", [$year, $department])->results();
    $batch_id = $students->get_batch_id($year, $department);
    $subject_id_arrays = $students->get_subject_id($batch_id, $sem);
    foreach ($subject_id_arrays as $i => $subject_id_array) {
        if ($subject == $subject_id_array->subject) {
            $subject_id = $subject_id_array->id;
        }
    }
?>
<ul>
    <a  style="text-decoration: none; color:black" href="dashboard.php?src=viewuniversitymark">View University Marks</a>
    
        > <a style="text-decoration: none; color:blue" href="#">View & Edit UniversityMark</a> 
</ul>
    <table>
        <thead>
            <tr>
                <th>Joining Year</th>
                <th>Select Department</th>
                <th>Select Semester</th>
                <th>Exam Type</th>
                <th>Subject</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= escape($year); ?></td>
                <td><?= escape($department); ?></td>
                <td><?= escape($sem); ?></td>
                <td><?= escape($exam); ?></td>
                <td><?= escape($subject); ?></td>
            </tr>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <th>Register Number</th>
                <th>Student Name</th>
                <th>Subject</th>
                <th>Marks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="studentMarks">
            <?php if (empty($results)): ?>
                <tr>
                    <td colspan="5">No Students Found.</td>
                </tr>
            <?php else:
                $i = 1;
                foreach ($results as $student):
                    $marks = $students->get_marks($batch_id, $subject_id, $student->id);
                    $mark = !empty($marks) ? $marks[0]->marks : 'A'; 
            ?>
                <tr>
                    <td><?= escape($student->Register_number); ?></td>
                    <td><?= escape($student->Name); ?></td>
                    <td><?= escape($subject); ?></td>
                    <td><?= escape($mark); ?></td> 
                    <td>
                        <form method="post" action="backend/edituniversitymark.php" id="editMarks<?= $i ?>">
                            <input type="hidden" value="<?= escape($student->id); ?>" name="student_id">
                            <input type="hidden" value="<?= escape($batch_id); ?>" name="batch_id">
                            <input type="hidden" value="<?= escape($subject_id); ?>" name="subject_id">
                            <input type="hidden" value="<?= escape($exam); ?>" name="exam">
                            <div class="button-container">
                                <button type="button" class="edit-btn" onclick="editMarks(<?= $i ?>)">Edit</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php 
                $i++;
                endforeach; 
            endif; ?>
        </tbody>
    </table>
    <div class="button-container">
        <a href="./dashboard.php?src=viewuniversitymark">
            <button id="back">Back</button>
        </a>
    </div>
    <?php 
    clearstatcache();
    ?>

<?php
else:
    echo "Error: No data submitted.";
endif;
?>

<script>
    function editMarks(index) {
        const form = document.getElementById("editMarks" + index);
        const student_id = form.querySelector('input[name="student_id"]').value;
        const subject_id = form.querySelector('input[name="subject_id"]').value;
        const batch_id = form.querySelector('input[name="batch_id"]').value;
        const exam = form.querySelector('input[name="exam"]').value;
        const currentMark = form.parentElement.parentElement.querySelector('td:nth-child(4)').textContent;

        const newMark = prompt("Enter the new mark for student ID " + student_id + " (current mark: " + currentMark + "):", currentMark);
        if (newMark !== null && newMark !== "") {
            const formData = new URLSearchParams();
            formData.append("student_id", student_id);
            formData.append("subject_id", subject_id);
            formData.append("batch_id", batch_id);
            formData.append("mark", newMark);
            formData.append("exam", exam);

            fetch("backend/edituniversitymark.php", {
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
</script>