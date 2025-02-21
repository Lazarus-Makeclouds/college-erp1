<?php
$db = DB::getInstance();
?>

<body>
    <section>
        <h2>Upload Question Bank (PDF)</h2>
        <form id="uploadForm" enctype="multipart/form-data" method="post">
            <!-- Select year -->
            <label for="year">Regulation Year :</label>
            <select id="year" name="year" required onclick="fetchDpt()">
                <option value="">--Select year--</option>
                <?php
                $years = $db->get_all("regulationyear", "year", true)->results();
                if (!empty($years)) {
                    foreach ($years as $year) {
                        echo "<option value='$year->year'>$year->year Reg-year($year->regulation)</option>";                    }
                } else {
                    echo "<option value=''>Add Regulation years</option>";
                }
                ?>
            </select>

            <label for="department">Department :</label>
            <select id="department" name="department" required>
                <option value=''>--Select Department--</option>
            </select>

            <label for="sem">Select Semester:</label>
            <select id="sem" name="sem" required>
                <option value="">--Select Semester--</option>
            </select>


            <!-- Subject Name -->
            <label for="subject">Subject :</label>
            <select id="subject" name="subject" required>
                <option value=''>--Select Subject--</option>
            </select>


            <!-- Subject Code -->
            <label for="subject_code">Subject Code :</label>
            <input type="text" id="subjectCode" name="subject_code" placeholder="Enter subject code (e.g., CS101)" required>

            <!-- Semester -->
            <label for="exam">Exam :</label>
            <select id="exam" name="exam" required>
                <option value="">--Select Exam--</option>
                <option value="1 Internal">1st Internal</option>
                <option value="2 Internal">2nd Internal</option>
                <option value="3 Internal">3rd Internal</option>
                <option value="1 Model">1st Model</option>
                <option value="2 Model">2nd Model</option>
                <option value="3 Model">3rd Model</option>
                <option value="Semester">Semester</option>
            </select>

            <!-- Upload PDF -->
            <label for="PDF">Upload PDF:</label>
            <input type="file" id="PDF" name="PDF" accept="application/pdf" required>
            <iframe id="pdfPreview" src=""></iframe>

            <input type="submit" value="Upload PDF">
        </form>
    </section>
    <script>
        let department_dropdown = document.getElementById("department");
        var sem_dropdown = document.getElementById("sem");
        let subject_dropdown = document.getElementById("subject");

        document.getElementById("PDF").addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const fileURL = URL.createObjectURL(file);
                const pdfPreview = document.getElementById("pdfPreview");
                pdfPreview.src = fileURL;
                pdfPreview.style.display = "block";
            }
        });

        document.getElementById("uploadForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = document.getElementById("uploadForm");
            const formData = new FormData(form);
            console.log(formData);
            fetch("backend/addquestionbank.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                    // console.log(data);
                })
                .catch(error => console.error("Error:", error));
        });

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