<?php
// Assuming you have a DB class for database operations
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
    <a style="text-decoration: none; color:black" href="dashboard.php?src=adduniversitymark">Add Semester Marks</a>
    > <a style="text-decoration: none; color:blue" href="#">Add Semester Mark For Each Subject</a> 
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
            <td><?= htmlspecialchars($year); ?></td>
            <td><?= htmlspecialchars($department); ?></td>
            <td><?= htmlspecialchars($sem); ?></td>
            <td><?= htmlspecialchars($exam); ?></td>
            <td><?= htmlspecialchars($subject); ?></td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th>Register Number</th>
            <th>Student Name</th>
            <th>Subject</th>
        </tr>
    </thead>
    <tbody id="studentMarks">
        <?php if (empty($results)): ?>
            <tr>
                <td colspan="3">No Students Found.</td>
            </tr>
        <?php else:
            $i = 1;
            foreach ($results as $student):
        ?>
            <tr>
                <td><?= escape($student->Register_number); ?></td>
                <td><?= escape($student->Name); ?></td>
                <td>
                    <input type="hidden" name="student_id[]" value="<?= escape($student->id); ?>" id="student_id<?= $i ?>">
                    <input type="hidden" id="batch_id<?= $i ?>" value="<?= escape($batch_id); ?>">
                    <input type="hidden" id="subject_id<?= $i ?>" value="<?= escape($subject_id); ?>">
                    <?php
                    $marks = $students->get_marks($batch_id, $subject_id, $student->id);
                    if (!empty($marks)):
                        $mark = $marks[0]->marks;
                    ?>
                        <input type="number" id="Mark<?= $i ?>" value="<?= escape($mark); ?>" readonly style="width: 60px;">
                    <?php
                    else:
                    ?>
                        <input type="number" id="Mark<?= $i ?>" style="width: 60px;" oninput="toggleSubmitButton(<?= $i ?>)">
                    <?php
                    endif;
                    ?>
                </td>
            </tr>
        <?php 
            $i++;
            endforeach; 
        endif; 
        ?>
    </tbody>
</table>
<div class="button-container">
    <button id="submitAll">Submit All Marks</button>
    <a href="./dashboard.php?src=adduniversitymark">
        <button>Back</button>
    </a>
</div>

<?php
else:
    echo "Error: No data submitted.";
endif;
?>
<script>
    function toggleSubmitButton(index) {
        const inputField = document.getElementById('Mark' + index);
        // Show the submit button if the input has a value, otherwise hide it
        if (inputField.value.trim() !== "") {
            document.getElementById('submit' + index).style.display = "inline-block";
        } else {
            document.getElementById('submit' + index).style.display = "none";
        }
    }

    // Submit all marks
    document.getElementById("submitAll").addEventListener("click", function() {
        const marksData = [];
        const totalStudents = <?= count($results); ?>; // Get the total number of students

        for (let i = 1; i <= totalStudents; i++) {
            const markInput = document.getElementById("Mark" + i);
            if (markInput && markInput.value.trim() !== "") {
                marksData.push({
                    student_id: document.getElementById("student_id" + i).value,
                    subject_id: document.getElementById("subject_id" + i).value,
                    batch_id: document.getElementById("batch_id" + i).value,
                    mark: markInput.value
                });
            }
        }

        if (marksData.length > 0) {
            const formData = new URLSearchParams();
            marksData.forEach(data => {
                formData.append("marks[]", JSON.stringify(data));
            });

            fetch("backend/addMarks.php", {
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
        } else {
            alert("Please enter marks for at least one student.");
        }
    });
</script>