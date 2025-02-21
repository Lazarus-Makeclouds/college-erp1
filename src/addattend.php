<?php
require_once "./core/init.php";

// Initialize Students and DB instances
$students = new Students();
$db = DB::getInstance();

// Check if year and department are set in POST request
if (isset($_POST['year']) && isset($_POST['department'])) {
    $year = Input::get("year");
    $department = Input::get("department");
    $number_of_years = Input::get("numberOfYears");
    
    // Fetch batch ID and students list based on year and department
    $batch_id = $students->get_batch_id($year, $department);
    $students_list = $students->generate_students($year, $department);
    $number_of_years = $students->get_number_of_years($year, $department);
    
}
?>
<ul>
    <a  style="text-decoration: none; color:black" href="dashboard.php?src=addattendance">Add Attendance</a>
    
        > <a style="text-decoration: none; color:blue" href="#">Mark Attendance</a> 
</ul>
<div class="page-title">Manage Attendance</div>
<hr>
<form id="manage-attendance" method="POST" action="backend/save_attendance.php">
    <div class="card">
        <div class="card-body">

        <label for="year" class="form-label">Year To Mark Attendance</label>
<select id="year" name="year" required onchange="setDateInputRange()">
    <option value="">--Select Year--</option>
    <?php 
    $year = Input::get("year") ?: date('Y');
   
    
    for ($i = $year; $i <= $year + $number_of_years; $i++) {
        echo '<option value="' . $i . '">' . $i . '</option>';
    }
    ?>
</select>

            <label for="class_date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Attendance Sheet</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="attendance-tbl" class="table">
                    <colgroup>
                        <col width="40%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Students</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Holiday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Check/Uncheck All</td>
                            <td><input class="form-check-input checkAll" type="checkbox" id="PCheckAll"></td>
                            <td><input class="form-check-input checkAll" type="checkbox" id="ACheckAll"></td>
                            <td><input class="form-check-input checkAll" type="checkbox" id="HCheckAll"></td>
                        </tr>
                        <?php if (!empty($students_list)): ?>
                            <?php foreach ($students_list as $student): ?>
                                <tr class="student-row">
                                    <td>
                                        <input type="hidden" name="student_id[]" value="<?= $student->id ?>">
                                        <?= htmlspecialchars($student->Name) ?>
                                        <input type="hidden" name="batch_id[]" value="<?= $batch_id ?>">
                                    </td>
                                    <td><input class="form-check-input status_check" data-id="<?= $student->id ?>" type="checkbox" name="status[]" value="1"></td>
                                    <td><input class="form-check-input status_check" data-id="<?= $student->id ?>" type="checkbox" name="status[]" value="2"></td>
                                    <td><input class="form-check-input status_check" data-id="<?= $student->id ?>" type="checkbox" name="status[]" value="3"></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <button class="btn rounded-pill" type="submit">Save Attendance</button>
    </div>
    
</form>
<div class="button-container">
        <a href="./dashboard.php?src=addattendance">
            <button id="back">Back</button>
        </a>
    </div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        checkAllCount();

        // Event listener for individual status checkboxes
        document.querySelectorAll('.status_check').forEach(check => {
            check.addEventListener('change', function() {
                const studentId = this.dataset.id;
                const isChecked = this.checked;

                // Uncheck other status checkboxes for the same student
                if (isChecked) {
                    document.querySelectorAll(`.status_check[data-id='${studentId}']`).forEach(item => {
                        item.checked = false;
                    });
                    this.checked = true;
                }
                checkAllCount();
            });
        });

        // Event listener for check all checkboxes
        document.querySelectorAll('.checkAll').forEach(checkAll => {
            checkAll.addEventListener('change', function() {
                const isChecked = this.checked;
                const id = this.id;

                // Uncheck other checkAll checkboxes
                if (isChecked) {
                    document.querySelectorAll('.checkAll').forEach(otherCheck => {
                        if (otherCheck.id !== id && otherCheck.checked) {
                            otherCheck.checked = false;
                        }
                    });

                    // Uncheck all status checkboxes
                    document.querySelectorAll('.status_check').forEach(status => {
                        status.checked = false;
                    });

                    // Check the relevant status checkboxes
                    const statusValue = {
                        PCheckAll: "1",
                        ACheckAll: "2",
                        HCheckAll: "3"
                    } [id];

                    if (statusValue) {
                        document.querySelectorAll(`.status_check[value="${statusValue}"]`).forEach(item => {
                            item.checked = true;
                        });
                    }
                } else {
                    // Uncheck the relevant status checkboxes
                    const statusValue = {
                        PCheckAll: "1",
                        ACheckAll: "2",
                        HCheckAll: "3"
                    } [id];

                    if (statusValue) {
                        document.querySelectorAll(`.status_check[value="${statusValue}"]`).forEach(item => {
                            item.checked = false;
                        });
                    }
                }
            });
        });

        // Function to check if all status checkboxes are checked
        function checkAllCount() {
            const statuses = {
                PCheckAll: "1",
                ACheckAll: "2",
                HCheckAll: "3"
            };

            document.querySelectorAll('.checkAll').forEach(checkAll => {
                const id = checkAll.id;
                const checkedCount = document.querySelectorAll(`.status_check[value="${statuses[id]}"]:checked`).length;
                const totalCount = document.querySelectorAll(`.status_check[value="${statuses[id]}"]`).length;

                checkAll.checked = checkedCount === totalCount;
            });
        }

        // Form submission handling
        document.getElementById('manage-attendance').addEventListener('submit', function(e) {
            e.preventDefault();

            let allMarked = true;
            document.querySelectorAll('#attendance-tbl .student-row').forEach(row => {
                const hasChecks = row.querySelectorAll('.status_check:checked').length;
                if (hasChecks < 1) {
                    const name = row.querySelector('td').innerText.trim();
                    alert(`${name}'s attendance is not yet marked!`);
                    allMarked = false;
                }
            });

            if (!allMarked) return;

            const formData = new URLSearchParams(new FormData(this)).toString();

            fetch("backend/save_attendance.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || "Attendance saved successfully!");
                    location.reload();
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while saving attendance. Please try again.");
                });
        });
    });

    
    

    function setDateInputRange() {
        const year = document.getElementById("year").value;
        const dateInput = document.getElementById("date");

        if (year) {
            dateInput.min = `${year}-01-01`; // Set minimum date to January 1st of the selected year
            dateInput.max = `${year}-12-31`; // Set maximum date to December 31st of the selected year
        } else {
            dateInput.min = ""; // Reset min if no year is selected
            dateInput.max = ""; // Reset max if no year is selected
        }
    }
</script>
