<body>
    <div class="schedule">
        <h1>Schedule Timetable </h1>
        <form method="POST" action="" id="timetable" enctype="multipart/form-data">
            <div class="upload-container">
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
                <input type="file" id="fileInput" name="fileInput" accept="image/*" class="file-input" hidden onchange="previewImage(event)">
                <label for="fileInput" class="file-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M11 20H6.5q-2.275 0-3.887-1.575T1 14.575q0-1.95 1.175-3.475T5.25 9.15q.625-2.3 2.5-3.725T12 4q2.925 0 4.963 2.038T19 11q1.725.2 2.863 1.488T23 15.5q0 1.875-1.312 3.188T18.5 20H13v-7.15l1.6 1.55L16 13l-4-4l-4 4l1.4 1.4l1.6-1.55z" />
                    </svg>
                    
                    <span>Upload Image</span>
                </label>
                <div class="file-name" id="filename"></div>
            </div>
            <div class="input-group">
                <label for="from">Start Date</label>
                <input type="date" id="from" name="from" required>
            </div>

            <div class="input-group">
                <label for="to">End Date</label>
                <input type="date" id="to" name="to" required>
            </div>

            <button type="submit" class="submit-btn" id="submit">Submit</button>
        </form>
    </div>
</body>

<script>
    document.getElementById("timetable").addEventListener("submit", function(e) {
        e.preventDefault();
        const form = document.getElementById("timetable");
        const formData = new FormData(form);
        fetch("backend/addtimetable.php", {
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

    function previewImage(event) {
        const fileName = event.target.files[0].name;
        document.getElementById('filename').textContent = fileName;
    }

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

    department_dropdown.onchange = function() {
        fetchSem();
    }
</script>

</html>