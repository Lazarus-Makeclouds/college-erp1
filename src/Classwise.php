<?php
$db = DB::getInstance();
$student = new Students();
?>

<body>
    <h1 style="text-align: center;"> Student Classwise Marks </h1>

    <form id="marksForm" action="./dashboard.php?src=addmarkinternal" method="POST">

        <label for="year">Joining Year:</label>
        <select id="year" name="year" required onclick="fetchDpt()">
            <option value="">--Select Year--</option>
            <?php
            $years = $db->get_all("regulationyear", "year", true)->results();
            if (!empty($years)) {
                foreach ($years as $year) {
                    echo "<option value='$year->year'>$year->year Reg-year($year->regulation)</option>";
                }
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

</html>



<div>
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Paper Name</th>
                <th>Staff Name</th>
                <th>Strength</th>
                <th>Appeared</th>
                <th>No. of Pass</th>
                <th>No. of Fail</th>
                <th>%</th>
                <th>Sub. Avg.</th>
                <th>Lowest Mark</th>
                <th>Highest Mark</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Tamil</td>
                <td>R. Nandhini</td>
                <td>46</td>
                <td>45</td>
                <td>39</td>
                <td>6</td>
                <td>87</td>
                <td>25</td>
                <td>3</td>
                <td>36</td>
            </tr>
            <tr>
                <td>2</td>
                <td>English</td>
                <td>R. S. Kannan</td>
                <td>46</td>
                <td>44</td>
                <td>31</td>
                <td>13</td>
                <td>70</td>
                <td>24</td>
                <td>8</td>
                <td>38</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Python Programming</td>
                <td>M. Santhosh</td>
                <td>46</td>
                <td>44</td>
                <td>34</td>
                <td>10</td>
                <td>77</td>
                <td>25</td>
                <td>13</td>
                <td>36</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Allied Maths</td>
                <td>S. Kalaiyarasan</td>
                <td>46</td>
                <td>45</td>
                <td>44</td>
                <td>1</td>
                <td>98</td>
                <td>35</td>
                <td>20</td>
                <td>45</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Problem Solving Tech</td>
                <td>K. Divyadharshini</td>
                <td>46</td>
                <td>44</td>
                <td>43</td>
                <td>1</td>
                <td>98</td>
                <td>35</td>
                <td>14</td>
                <td>45</td>
            </tr>
            <tr>
                <td>6</td>
                <td>NME</td>
                <td>S. Deivanai</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>#DIV/0!</td>
                <td>#DIV/0!</td>
                <td>0</td>
                <td>0</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="11">Total Number of Students: 46</td>
            </tr>
            <tr>
                <td colspan="11">Number of All Clear Students: 28</td>
            </tr>
            <tr>
                <td colspan="11">Overall Percentage: 61%</td>
            </tr>
        </tfoot>
    </table>
</div>