<?php
$db = DB::getInstance();
$student = new Students();
if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $user = $db->get("students_details", ["Email", "=", $user_email])->results();

    if ($user) {
        $department = $user[0]->Department; 
        $year_of_joining = $user[0]->Joining_year; 
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Please log in to view your details.";
    exit();
}

?>
<ul>
    <a  style="text-decoration: none; color:blue" href="dashboard.php?src=viewparticularstdattendance">View Attendance</a>
    > <a style="text-decoration: none; color:black" href="#"></a> 
</ul>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
</head>

<body>

    <form id="marksForm" action="./dashboard.php?src=attendancestudent" method="POST">
        <label for="Joining_year">Year of joining</label>
        <select id="Joining_year" name="Joining_year" required onchange="fetchDpt()">
            <option value="">-- Select Year --</option>
            <?php
            $years = $db->get_all("regulationyear", "year", true)->results();
            if (!empty($years)) {
                foreach ($years as $year) {
                    echo "<option value='$year->year'>$year->year Reg-year($year->regulation)</option>";                            
                }
            } else {
                echo "<option value=''>Add Regulation years</option>";
            }
            ?>
        </select>

        <label for="department">Select Department:</label>
        <select id="department" name="department" required>
            <option value="">--Select Department--</option>
        </select>

        <label for="sem">Select Semester:</label>
        <select id="sem" name="sem" required>
            <option value="">-- Select Semester --</option>
        </select>

        <label for="YoP">Year of Passing Out</label>
        <input type="number" id="YoP" name="YoP" readonly>

        <label for="Number_of_years">Total Number Of Years</label>
        <select name="Number_of_years" id="Number_of_years" onchange="fetchStudentsByYear()">
            <option value="">-- Select Year --</option>
        </select>

        <label for="monthPicker">Select Month:</label>
        <select id="monthPicker" name="monthPicker" required>
            <option value="">-- Select Month --</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>

        <button type="submit" id="generateStudents" onclick="generateStudents()">Generate Students</button>
    </form>

    <script>
        var department_dropdown = document.getElementById("department"); // Correct ID for department dropdown
        const semDropdown = document.getElementById("sem");

        async function fetchDpt() {
            let year = document.getElementById("Joining_year").value; // Corrected the year ID
            var response = await fetch("<?= $_ENV['API_URL']; ?>/backend/api/deptfilter.php?year=" + year);
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
            const year = document.getElementById("Joining_year").value;
            const dept = department_dropdown.value;
            if (!year || !dept) return;

            try {
                const response = await fetch(`<?= $_ENV["API_URL"]; ?>/backend/api/deptfilter.php?year=${year}&dept=${dept}`);
                const data = await response.json();
                const semesters = data[0]; // Assuming the first element contains the number of semesters
                semDropdown.innerHTML = "<option value=''>--Select Semester--</option>";
                for (let j = 1; j <= semesters; j++) {
                    const option = document.createElement("option");
                    option.text = j;
                    option.value = j;
                    semDropdown.appendChild(option);
                }
            } catch (error) {
                console.error("Error fetching semesters:", error);
            }
        }

        async function fetchYear() {
            let year = document.getElementById("Joining_year").value;
            let dept = document.getElementById("department").value;

            var response = await fetch("<?= $_ENV['API_URL']; ?>/backend/api/yearFilter.php?year=" + year + "&dept=" + dept);
            var data = await response.json();

            let YoP = document.getElementById("YoP");
            let NoY = document.getElementById("Number_of_years");

            let joiningYear = Number(data.Year_of_joining);
            let numberOfYears = Number(data.Number_of_years);

            let yop = joiningYear + numberOfYears;
            YoP.value = yop;

            let yearsList = [];
            for (let i = joiningYear; i <= yop; i++) {
                yearsList.push(i);
            }

            NoY.innerHTML = '';
            NoY.innerHTML = '<option value="">-- Select Year --</option>';

            yearsList.forEach(year => {
                let option = document.createElement("option");
                option.value = year;
                option.text = year;
                NoY.appendChild(option);
            });
        }

        department_dropdown.onchange = function() {
            fetchSem();
            fetchYear();
        };

        window.onload = function() {
            fetchDpt();
        };

        async function generateStudents() {
            const year = document.getElementById("Joining_year").value;
            const dept = department_dropdown.value;
            const sem = semDropdown.value;
            const month = document.getElementById("monthPicker").value;

            try {
                const response = await fetch(`<?= $_ENV["API_URL"]; ?>/backend/api/studentdata.php?year=${year}&dept=${dept}&sem=${sem}&month=${month}`);
                const data = await response.json();

                const tbody = document.getElementById("studentsData");
                tbody.innerHTML = ''; // Clear previous data

                if (data.length > 0) {
                    data.forEach(student => {
                        const attendanceHTML = student.attendance.map(date => `<span>${date}</span>`).join(", ");
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${student.name}</td>
                            <td>${student.roll_number}</td>
                            <td>${student.department}</td>
                            <td>${student.semester}</td>
                            <td>${attendanceHTML}</td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5">No students found for the selected criteria.</td></tr>';
                }
            } catch (error) {
                console.error("Error generating students:", error);
            }
        }

        async function fetchStudentsByYear() {
            const selectedYear = document.getElementById("Number_of_years").value;
            const dept = document.getElementById("department").value;
            const sem = document.getElementById("sem").value;
            const month = document.getElementById("monthPicker").value;

            try {
                const response = await fetch(`<?= $_ENV["API_URL"]; ?>/backend/api/studentdata.php?year=${selectedYear}&dept=${dept}&sem=${sem}&month=${month}`);
                const data = await response.json();

                const tbody = document.getElementById("studentsData");
                tbody.innerHTML = ''; // Clear previous data

                if (data.length > 0) {
                    data.forEach(student => {
                        const attendanceHTML = student.attendance.map(date => `<span>${date}</span>`).join(", ");
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${student.name}</td>
                            <td>${student.roll_number}</td>
                            <td>${student.department}</td>
                            <td>${student.semester}</td>
                            <td>${attendanceHTML}</td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5">No students found for the selected criteria.</td></tr>';
                }
            } catch (error) {
                console.error("Error fetching student data by year:", error);
            }
        }
    </script>
</body>

</html>
