<?php
$db = DB::getInstance();
$student = new Students();
?>
<ul>
    <a  style="text-decoration: none; color:blue" href="dashboard.php?src=addinternalmark">Add InternalMark</a>
    
        > <a style="text-decoration: none; color:black" href="dashboard.php?src=#"></a>
    
    
</ul>
<body>
    <h1 style="text-align: center;"> Student Internal Marks </h1>

    <form id="marksForm" action="./dashboard.php?src=addmarkinternal" method="POST">

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
            <option value="">--Select Semester--</option>
        </select>

        <label for="exam">Exam type :</label>
        <select id="exam" name="exam" required>
            <option value="">--Exam type--</option>
            <option value="1st Internal">1st Internal</option>
            <option value="2nd Internal">2nd Internal</option>
            <option value="3rd Internal">3rd Internal</option>
            <option value="4th Internal">4th Internal</option>
            <option value="5th Internal">5th Internal</option>
            <option value="Model-1">Model-1</option>
            <option value="Model-2">Model-2</option>
            <option value="Model-3">Model-3</option>
            <option value="Model-4">Model-4</option>
            <option value="Model-5">Model-5</option>
        </select>
        </select>

        <label for="subject"> Select Subject :</label>
        <select id="subject" name="subject" required>
            <option value="">--Select Subject--</option>
        </select>

        <button type="submit" id="generateStudents">Generate Students</button>

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
         let subject =document.getElementById("subject").value;
         var response = await fetch("<?= $_ENV["API_URL"];?>/backend/api/deptfilter.php?year=" + year + "&dept=" + dept + "&sem=" + sem + "&subject" + subject );
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

</html>


