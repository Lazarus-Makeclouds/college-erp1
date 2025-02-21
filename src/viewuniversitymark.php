<?php
$db = DB::getInstance();
$student = new Students();
?>
<ul>
    <a  style="text-decoration: none; color:blue" href="dashboard.php?src=viewuniversitymark">View University Marks</a>
    
        > <a style="text-decoration: none; color:black" href="#"></a> 
</ul>
<body>
    <h1 style="text-align: center;"> Student Marks </h1>

    <form id="marksForm" action="./dashboard.php?src=universityaddmark" method="POST">

        <label for="year">Joining Year:</label>
        <select id="year" name="year" required onclick="fetchDpt()">
            <option value="">--Select Year--</option>
            <?php
            $years = $db->get_all("regulationyear", "year", true)->results();
            if (!empty($years)) {
                foreach ($years as $year) {
                    echo "<option value='$year->year'>$year->year Reg-year($year->regulation)</option>";                }
            } else {
                echo "<option value=''>Add Joinign years</option>";
            }
            ?>
        </select>
        <label for="department">Select Department:</label>
        <select id="department" name="department" required>
            <option value="">--Select Department--</option>
        </select>

        <label for="sem">Select Semester:</label>
        <select id="sem" name="sem" required>
            <option value="">--Select Year--</option>
        </select>

        <label for="exam">Exam type :</label>
        <select id="exam" name="exam">
            <option value="Semester">Semester Exam</option>
        </select>

        <label for="subject"> Select Subject :</label>
        <select id="subject" name="subject" required>
            <option value="">--Select Subject--</option>
        </select>

        <button type="submit" id="generateStudents">Generate Students</button>

    </form>
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
            console.log(i);
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
            let subject = document.getElementById("subject").value;
            var response = await fetch("<?= $_ENV["API_URL"]; ?>/backend/api/deptfilter.php?year=" + year + "&dept=" + dept + "&sem=" + sem + "&subject" + subject);
            var data = await response.json();
            console.log(data);
            subject_dropdown.innerHTML = "<option value=''>--Select Subject--</option>";
            data.forEach((item) => {
                var option = document.createElement("option");
                option.text = item;
                option.value = item;

                subject_dropdown.appendChild(option);
            });
        }

        department_dropdown.onchange = function() {
            fetchSem();
        }
        sem_dropdown.onchange = function() {
            fetchSubjects();
        }
    </script>

</body>

</html>