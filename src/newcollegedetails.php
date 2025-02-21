<?php
$db = DB::getInstance();
?>
<body>
    <section>
        <form action="" id="Regulation" method="post">
        <label for="Year_of_joining">Regulation Year</label>
        <input type="number" min="1950" max="2099" step="1" placeholder="<?= date("Y"); ?>" id="regulation_joining" />
            <label for="Year_of_joining">Year of joining</label>
            <input type="number" min="1950" max="2099" step="1" placeholder="<?= date("Y"); ?>" id="Year_of_joining" />
            
            <input type="submit" value="Submit">
        </form>
    </section>

    <section>
        <form action="" method="post" id="addBatch">
            <h1 class="heading">Add Course Details</h1>
            <label for="Joining_year">Year of joining</label>
            <select id="Joining_year" name="Joining_year" required>
                <?php
                $years = $db->get_all("regulationyear", "year","regulation", true)->results();
                if (!empty($years)) {
                    foreach ($years as $year) {
                        echo "<option value='$year->year'>$year->year Reg-year($year->regulation)</option>";
                       
                    }
                } else {
                    echo "<option value=''>Add Regulation years</option>";
                }
                ?>
            </select>
            
            <label for="">Department</label>
            <select id="Department" name="Department" required>
                <option value="">Select department</option>
                <option value="CS">CS</option>
                <option value="BCA">BCA</option>
                <option value="AIDS">AIDS</option>
                <option value="BBA">BBA</option>
                <option value="B.com">B.com</option>
                <option value="B.com(CA)">B.com(CA)</option>
                <option value="BSC(Maths)">BSC(Maths)</option>
                <option value="BSC(Physics)">BSC(Physics)</option>
                <option value="BSC(Botany)">BSC(Botany)</option>
                <option value="BSC(Chemistry)">BSC(Chemistry)</option>
                <option value="Bio(chemistry)">Bio(chemistry)</option>
                <option value="Bio(Tech)">Bio(Tech)</option>
                <option value="Micro-bio">Micro-bio</option>
                <option value="PG MSc">PG MSc</option>
                <option value="PG M.Com">PG M.com</option>
            </select>
            <label for="Number_of_years">Total Number Of Year</label>
            <input type="number" name="Number_of_years" id="Number_of_years" oninput="numberOfsem()">
            <label for="Number_of_semesters">Total Number Of Semester</label>
            <input type="number" name="Number_of_semesters" id="Number_of_semesters" readonly>
            <input type="submit" value="Submit">
        </form>
    </section>
    <section class="departlist">
        <div class="table-child">
            <table>
                <caption>
                    Department List
                </caption>
                <thead>
                    <tr>
                        <th>Year of Joining</th>
                        <th>Number of Department</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query = "SELECT b.Year_of_joining, b.Department, CONCAT(b.Year_of_joining, ' (', r.regulation, ')') 
                AS Year_Regulation FROM batch b
                LEFT JOIN regulationyear r ON b.Year_of_joining = r.year";
                $results = $db->query($query);
    
                foreach ($results->results() as $result) : ?>
                <tr>
                    <td><?= $result->Year_Regulation; ?></td> 
                    <td><?= $result->Department; ?></td>
                </tr>
                <?php endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
<script>
    function numberOfsem() {
        let noy = document.getElementById("Number_of_years").value;
        let nos = noy * 2;
        document.getElementById("Number_of_semesters").value = nos;
    }

    document.getElementById("Regulation").addEventListener("submit", function(e) {
        e.preventDefault();
        const join_year = document.getElementById("Year_of_joining").value;
        const Reg_year =document.getElementById("regulation_joining").value;

        const formData = new URLSearchParams();
        formData.append("regulation", Reg_year);
        formData.append("year",join_year);
        fetch("backend/addRegYear.php", {
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
    });

    document.getElementById("addBatch").addEventListener("submit", function(e) {
        e.preventDefault();
        const Year_of_joining = document.getElementById("Joining_year").value;
        const Department = document.getElementById("Department").value;
        const Number_of_years = document.getElementById("Number_of_years").value;
        const Number_of_semesters = document.getElementById("Number_of_semesters").value;

        const formData = new URLSearchParams();
        formData.append("Year_of_joining", Year_of_joining);
        formData.append("Department", Department);
        formData.append("Number_of_years", Number_of_years);
        formData.append("Number_of_semesters", Number_of_semesters);
        fetch("backend/addBatch.php", {
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
    });
</script>

</html>