<?php
$db = DB::getInstance();
?>
<body>
    <main>
        <section class="form-section">
            <h2>Teacher's Details</h2>
            <form action="" method="post" enctype="multipart/form-data" id="newStaff">
                <!-- Personal Information -->
                <fieldset>
                    <legend>Personal Information</legend>
                    <label for="Image">Teacher's Image</label>
                    <input type="file" id="Image" name="Image" required>

                    <label for="Name">Teacher's Name</label>
                    <input type="text" id="Name" name="Name" required>

                    <label for="Nationality">Nationality</label>
                    <input type="text" id="Nationality" name="Nationality" required>

                    <label for="Marital_Status">Marital Status</label>
                    <select id="Marital_Status" name="Marital_Status" required>
                        <option value="">Select</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                    </select>

                    <label for="Husband_Name">Spouse Name</label>
                    <input type="text" id="Husband_Name" name="Husband_Name">

                    <label for="DoB">Date of Birth</label>
                    <input type="date" id="DoB" name="DoB" required>

                    <label for="Email">Teacher's Email</label>
                    <input type="text" id="Email" name="Email" required>

                    <label for="Residential_Address">Residential Address</label>
                    <input type="text" id="Residential_Address" name="Residential_Address" required>
                </fieldset>

                <!-- Academic Details -->
                <fieldset>
                    <legend>Academic Details</legend>
                    <label for="Level">Level</label>
                    <input type="text" id="Level" name="Level" required>

                    <label for="FOS">Field Of Study </label>
                    <input type="text" id="FOS" name="FOS" required>

                    <label for="Major">Major</label>
                    <input type="text" id="Major" name="Major" required>

                    <label for="InstitudeUniversity">Institute/University</label>
                    <input type="text" id="InstitudeUniversity" name="InstitudeUniversity" required>

                    <label for="Locatedin">Located in</label>
                    <input type="text" id="Locatedin" name="Locatedin" required>

                    <label for="Graduation">Graduation Year</label>
                    <input type="month" id="Graduation" name="Graduation" required>
                </fieldset>

                <!-- Family Information -->
                <fieldset>
                    <legend>Family Information</legend>
                    <label for="Father_Name">Father's Name</label>
                    <input type="text" id="Father_Name" name="Father_Name" required>

                    <label for="Mother_Name">Mother's Name</label>
                    <input type="text" id="Mother_Name" name="Mother_Name" required>
                </fieldset>

                <!-- Contact Information -->
                <fieldset>
                    <legend>Contact Information</legend>
                    <label for="Mobile_no">Mobile Number</label>
                    <input type="text" id="Mobile_no" name="Mobile_no" required>
                </fieldset>

                <!-- Work Information -->
                <fieldset>
                    <legend>Work Information</legend>
                    <label for="InstitutionName">Institution Name</label>
                    <input type="text" id="InstitutionName" name="InstitutionName" required>

                    <label for="PositionTitle">Position Title</label>
                    <input type="text" id="PositionTitle" name="PositionTitle" required>

                    <label for="PositionLevel">Position Level</label>
                    <input type="text" id="PositionLevel" name="PositionLevel" required>

                    <label for="Specialization">Specialization</label>
                    <input type="text" id="Specialization" name="Specialization" required>

                    <label for="Industry">Industry</label>
                    <input type="text" id="Industry" name="Industry" required>

                    <label for="DoJ">Date Joined</label>
                    <input type="date" id="DoJ" name="DoJ" required>
                </fieldset>

                <!-- Skills and Languages -->
                <fieldset>
                    <legend>Skills and Languages</legend>
                    <label for="Skill">Skill</label>
                    <input type="text" id="Skill" name="Skill" required>

                    <label for="Language">Language</label>
                    <input type="text" id="Language" name="Language" required>
                </fieldset>

                <input type="submit" value="Save Changes">
            </form>
        </section>
    </main>
    <script>
    document.getElementById("newStaff").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = document.getElementById("newStaff");
        const formData = new FormData(form);
        fetch("backend/addStaff.php", {
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
</body>
