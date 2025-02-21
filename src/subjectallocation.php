<?php
// Assuming DB::getInstance() is working
$db = DB::getInstance();


if (Input::exists()):
    $year = Input::get("Year");
    $name = Input::get("Name");
    if (empty($year) & empty($name)) {
        $results = $db->get_all("teachers_details", "Name", true)->results();
    } elseif (empty($name)) {
        $results = $db->get("teachers_details", ["YEAR(`DoJ`)", "=", $year], "Name", true)->results();
    } elseif (empty($year)) {
        $results = $db->get("teachers_details", ["Name", "=", $name], "YEAR(`DoJ`)", true)->results();
    } else {
        $results = $db->query("SELECT * FROM `teachers_details` WHERE YEAR(`DoJ`) = ? AND `Name` = ? ORDER BY `Name` ASC", [$year, $name])->results();
    }
else:
    $results = $db->get_all("teachers_details", "Name", true)->results();
endif;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Allocation</title>
</head>
<body>

<h2>Subject Allocation Form</h2>

<form method="POST" action="" id="addsubject">
    <label for="teacher_id">Select Teacher:</label>
    <select name="teacher_id" id="Name" required>
        <option value="">Select Teacher</option>
        <?php
         $results = $db->get_all("teachers_details", "Name", true)->results();
        if (!empty($results)) {
            foreach ($results as $Name) {
                echo "<option value='$Name->Name'>$Name->Name</option>";
            }
        } else {
            echo "<option value=''>Add Joining years</option>";
        }
        ?>
        
    </select>
    <br><br>

    <label for="year">Student Joining Year:</label>
    <select id="year" name="year" required onclick="fetchDpt()">
        <option value="">--Select Year--</option>
        <?php
        $years = $db->get_all("regulationyear", "year", true)->results();
        if (!empty($years)) {
            foreach ($years as $year) {
                echo "<option value='$year->year'>$year->year Reg-year($year->regulation)</option>";
            }
        } else {
            echo "<option value=''>Add Joining years</option>";
        }
        ?>
    </select>

    <label for="department">Select Department:</label>
    <select id="department" name="department" required>
        <option value="">--Select Department--</option>
    </select>

    <label for="sem">Select Semester:</label>
    <select id="sem" name="sem" required>
        <option value="">--Select Semester--</option>
    </select>
    <br><br>

    <label for="subject">Select Subject:</label>
    <select id="subject" name="subject" required>
        <option value="">--Select Subject--</option>
    </select>
    <br><br>

    <button type="submit">Allocate Subject</button>
</form>

</body>

<script>
    var department_dropdown = document.getElementById("department");
    var sem_dropdown = document.getElementById("sem");
    let subject_dropdown = document.getElementById("subject");

    async function fetchDpt() {
        let year = document.getElementById("year").value;
        var response = await fetch("<?= $_ENV["API_URL"]; ?>/backend/api/deptfilter.php?year=" + year);
        var data = await response.json();
        department_dropdown.innerHTML = "<option value=''>--Select Department--</option>";
        data.forEach((item) => {
            var option = document.createElement("option");
            option.text = item;
            option.value = item;

            department_dropdown.appendChild(option);
        });
    }

    async function fetchSem() {
        let year = document.getElementById("year").value;
        let dept = document.getElementById("department").value;
        var response = await fetch("<?= $_ENV["API_URL"]; ?>/backend/api/deptfilter.php?year=" + year + "&dept=" + dept);
        var data = await response.json();
        i = data[0];
        sem_dropdown.innerHTML = "<option value=''>--Select Semester--</option>";
        for (let j = 1; j <= i; j++) {
            var option = document.createElement("option");
            option.text = j;
            option.value = j;

            sem_dropdown.appendChild(option);
        }
    }

    async function fetchSubjects() {
    let year = document.getElementById("year").value;
    let dept = document.getElementById("department").value;
    let sem = document.getElementById("sem").value;
    var response = await fetch("<?= $_ENV["API_URL"]; ?>/backend/api/deptfilter.php?year=" + year + "&dept=" + dept + "&sem=" + sem);
    var data = await response.json();
    
    subject_dropdown.innerHTML = "<option value=''>--Select Subject--</option>";
    
    // Fetch subjects and check if already allocated
    data.forEach((item) => {
        var option = document.createElement("option");
        option.text = item;
        option.value = item;
        
        // Check if the subject is already allocated
        checkSubjectAllocation(year, dept, item).then((isAllocated) => {
            if (isAllocated) {
                option.disabled = true; // Disable option if allocated
                option.style.color = 'red'; // Optional: style to indicate it's disabled
            }
        });

        subject_dropdown.appendChild(option);
    });
}

async function checkSubjectAllocation(year, dept, subject) {
    const response = await fetch("backend/check_subject_allocation.php?year=" + year + "&dept=" + dept + "&subject=" + subject);
    const data = await response.json();
    return data.isAllocated; // Assuming API returns { isAllocated: true/false }
}
    department_dropdown.onchange = function() {
        fetchSem();
    }

    sem_dropdown.onchange = function() {
        fetchSubjects();
    }
    document.getElementById("addsubject").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = document.getElementById("addsubject");
        const formData = new FormData(form);
        fetch("backend/addstaffsubject.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error("Error:", error));
    });
    
</script>

</html>
