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
    <a  style="text-decoration: none; color:black" href="dashboard.php?src=addstaffinternalmark">Add InternalMark</a>
    
        > <a style="text-decoration: none; color:blue" href="dashboard.php?src=addingmarkstaff">Add Student Internal Mark</a>
    
    
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
<form id="submitAllMarks">
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
                foreach ($results as $student):
            ?>
                <tr>
                    <td><?= escape($student->Register_number); ?></td>
                    <td><?= escape($student->Name); ?></td>
                    <td>
                        <input type="hidden" name="student_id[]" value="<?= escape($student->id); ?>">
                        <input type="hidden" name="batch_id[]" value="<?= escape($batch_id); ?>">
                        <input type="hidden" name="subject_id[]" value="<?= escape($subject_id); ?>">
                        <input type="hidden" name="exam[]" value="<?= escape($exam); ?>">
                        <?php
                        $marks = $students->get_marks($batch_id, $subject_id, $student->id, $exam);
                        if (!empty($marks)):
                            $mark = $marks[0]->marks;
                        ?>
                            <input type="number" name="mark[]" value="<?= $mark; ?>" readonly style="width: 60px;">
                        <?php
                        else:
                        ?>
                            <input type="number" name="mark[]" style="width: 60px;" oninput="toggleSubmitButton()">
                        <?php
                        endif;
                        ?>
                    </td>
                </tr>
            <?php 
                endforeach; 
            endif; 
            ?>
        </tbody>
    </table>
    <div class="button-container">
        <input type="submit" id="submitAll" value="Submit All">
    </div>
</form>
<div class="button-container">
    <a href="./dashboard.php?src=addinternalmark">
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
    document.getElementById("submitAllMarks").addEventListener("submit", function(e) {
        e.preventDefault(); // Prevent the default form submission
        const formData = new FormData(this); // Create FormData object
        fetch("backend/addMarksinternal.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Show success message
                location.reload(); // Reload the page
            })
            .catch(error => console.error("Error:", error)); // Log any errors
    });

    function toggleSubmitButton() {
        // You can implement any logic here if needed
    }
</script>