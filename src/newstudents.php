<?php
$db = DB::getInstance();
?>

<body>
    <style>
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #FFF;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
            display: none;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <main>
        <section class="form-section">
            <h2>Student Information Form</h2>
            <form action="" method="post" enctype="multipart/form-data" id="addStudent">

                <!-- Personal Information Section -->
                <fieldset>
                    <legend>Personal Information</legend>
                    <label for="Image">Student Image</label>
                    <input type="file" id="Image" name="Image">


                    <label for="Name">Name</label>
                    <input type="text" id="Name" name="Name" required>

                    <label for="Email">Email</label>
                    <input type="email" id="Email" name="Email" required>

                    <label for="Mobile_no">Mobile Number</label>
                    <input type="text" id="Mobile_no" name="Mobile_no">

                    <label for="DoB">Date of Birth</label>
                    <input type="date" id="DoB" name="DoB" required>

                    <label for="Community">Community</label>
                    <input type="text" id="Community" name="Community">

                    <label for="Religion">Religion</label>
                    <input type="text" id="Religion" name="Religion">

                    <label for="Nationality">Nationality</label>
                    <input type="text" id="Nationality" name="Nationality">
                </fieldset>

                <!-- Academic Information Section -->
                <fieldset>
                    <legend>Academic Information</legend>
                    <label for="Register_number">Registration No</label>
                    <input type="text" id="Register_number" name="Register_number">

                    <label for="Admission_number">Admission No</label>
                    <input type="text" id="Admission_number" name="Admission_number">

                    <label for="Joining_year">Year of joining</label>
                    <select id="Joining_year" name="Joining_year" required onchange="fetchDpt()">
                        <option value="">-- Select Year --</option>
                        <?php
                        $years = $db->get_all("regulationyear", "year", true)->results();
                        if (!empty($years)) {
                            foreach ($years as $year) {
                                echo "<option value='$year->year'>$year->year Reg-year($year->regulation)</option>";                            }
                        } else {
                            echo "<option value=''>Add Regulation years</option>";
                        }
                        ?>
                    </select>

                    <label for="Department">Department :</label>
                    <div>
                        <select id="Department" name="Department" required>
                            <option value="">-- Select Department --</option>
                        </select>
                        <div class="loader" id="departmentLoader"></div>
                    </div>

                    <label for="YoP">Year of Passing Out</label>
                    <input type="number" id="YoP" name="YoP" readonly>
                    <label for="Number_of_years">Total Number Of Year</label>
                    <input type="number" name="Number_of_years" id="Number_of_years" oninput="updateFields()" readonly>
                    <label for="Number_of_semesters">Total Number Of Semester</label>
                    <input type="number" name="Number_of_semesters" id="Number_of_semesters" readonly>


                    <label for="Marks10">10th Marks</label>
                    <input type="text" id="Mark10" name="Marks10">

                    <label for="Marks12">12th Marks</label>
                    <input type="text" id="Mark12" name="Marks12">

                    <label for="TC_issue">TC Issued Date</label>
                    <input type="date" id="TC_issue" name="TC_issue">

                    <label for="AbcId">ABC ID</label>
                    <input type="text" id="AbcId" name="AbcId">

                    <label for="Apaar">Apaar Number</label>
                    <input type="text" id="Apaar" name="Apaar">



                    <label for="Batch">Batch</label>
                    <input type="text" id="Batch" name="Batch">

                    <label for="Branch">Branch</label>
                    <input type="text" id="Branch" name="Branch">

                </fieldset>

                <!-- Family Information Section -->
                <fieldset>
                    <legend>Family Information</legend>
                    <label for="Father_Name">Father's Name</label>
                    <input type="text" id="Father_Name" name="Father_Name" required>

                    <label for="Mother_Name">Mother's Name</label>
                    <input type="text" id="Mother_Name" name="Mother_Name" required>

                    <label for="Mother_Tongue">Mother Tongue</label>
                    <input type="text" id="Mother_Tongue" name="Mother_Tongue">

                    <label for="Marital_Status">Marital Status</label>
                    <select id="Marital_Status" name="Marital_Status">
                        <option value="">Select</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                    </select>

                    <label for="Husband_Name">Spouse's Name</label>
                    <input type="text" id="Husband_Name" name="Husband_Name">
                </fieldset>

                <!-- Other Information Section -->
                <fieldset>
                    <legend>Other Information</legend>
                    <label for="Blood_group">Blood Group</label>
                    <input type="text" id="Blood_group" name="Blood_group">

                    <label for="Address">Address</label>
                    <textarea id="Residential_Address" name="Residential_Address"></textarea>

                    <label for="Aadhar_number">Aadhar Card Number</label>
                    <input type="text" id="Aadhar_number" name="Aadhar_number">
                </fieldset>

                <!-- Bank Information Section -->
                <fieldset>
                    <legend>Bank Information</legend>
                    <label for="BankName">Bank Name</label>
                    <input type="text" id="BankName" name="BankName">

                    <label for="Ifsc">IFSC Code</label>
                    <input type="text" id="Ifsc" name="Ifsc">

                    <label for="AccountNumber">Account Number</label>
                    <input type="text" id="AccountNumber" name="AccountNumber">
                </fieldset>

                <input type="submit" value="Submit">
            </form>
        </section>
    </main>

    <script>
        var department_dropdown = document.getElementById("Department");


        document.getElementById("addStudent").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = document.getElementById("addStudent");
            const formData = new FormData(form);

            fetch("backend/addStudent.php", {
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

        async function fetchDpt() {
            let year = document.getElementById("Joining_year").value;
            const loader = document.getElementById("departmentLoader");
            loader.style.display = "inline-block"; // Show loader

            try {
                // Fetch the department data
                var response = await fetch("<?= $_ENV['API_URL']; ?>/backend/api/deptfilter.php?year=" + year);
                var data = await response.json();

                // Populate the dropdown
                department_dropdown.innerHTML = "<option value=''>-- Select Department --</option>";
                data.forEach((item) => {
                    const option = document.createElement("option");
                    option.text = item;
                    option.value = item;
                    department_dropdown.appendChild(option);
                });
            } catch (error) {
                console.error("Error fetching departments:", error);
                alert("Failed to load departments. Please try again.");
            } finally {
                loader.style.display = "none"; // Hide loader
            }
        }


        async function fetchYear() {
            let year = document.getElementById("Joining_year").value;
            let dept = document.getElementById("Department").value;
            var response = await fetch("<?= $_ENV["API_URL"]; ?>/backend/api/yearFilter.php?year=" + year + "&dept=" + dept);
            var data = await response.json();
            console.log(data);

            let YoP = document.getElementById("YoP");
            let NoY = document.getElementById("Number_of_years");
            let NoS = document.getElementById("Number_of_semesters");
            YoP.value = Number(data.Year_of_joining) + Number(data.Number_of_years);
            NoY.value = data.Number_of_years;
            NoS.value = data.Number_of_semesters;
        }

        department_dropdown.onchange = function() {
            fetchYear();
        }
    </script>
</body>

</html>