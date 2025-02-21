<?php
$db = DB::getInstance();
require_once __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv as Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();
if (Input::exists("post")) {
    $year = Input::get("year");
    $department = Input::get("department");
    $results = $db->query("SELECT * FROM `batch` where `Year_of_joining` = ? AND `department` = ?", [$year, $department]);
    if (!empty($results->results())) {
        $year_of_joining = $results->first()->Year_of_joining;
        $department = $results->first()->Department;
        $no_of_years = $results->first()->Number_of_years;
        $no_of_sem = $results->first()->Number_of_semesters;
        $batch_id = $results->first()->id;
    } else {
        $department = $no_of_sem = "--";
        $no_of_years = 0;
        $year_of_joining = 0;
    }
} else {
    $department = $no_of_sem = "--";
    $no_of_years = 0;
    $year_of_joining = 0;
}
?>


<body>
    <div class="popup-overlay" id="popup">
        <div class="popup-content">
            <svg id="cancelbtn" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <rect width="24" height="24" fill="none" />
                <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m5 19l7-7m0 0l7-7m-7 7L5 5m7 7l7 7" />
            </svg>
            <form action="./dashboard.php?src=managecollegedetails" id="optionForm" method="post">
                <h2>Select Year and Department</h2>
                <select name="year" id="year" onclick="fetchDpt()">
                    <option value="">Select Year</option>
                    <?php
                    $results = $db->query("SELECT DISTINCT `Year_of_joining` FROM `batch`")->results();
                    foreach ($results as $row) {
                        echo "<option value='" . $row->Year_of_joining . "'>" . $row->Year_of_joining . "</option>";
                    }
                    ?>
                </select>
                <select name="department" id="department">
                    <option value="">Select Department</option>
                </select>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="details">
        <div class="popbtnmodal">
            <button class="onpopbtn"> Select year and department </button>
        </div>
        <section class="table1">
            <table>
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Year of Joining</th>
                        <th>Year of Ending</th>
                        <th>Number of Year</th>
                        <th>Number of Semester</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $department; ?></td>
                        <td><?= $year_of_joining; ?></td>
                        <td><?= $year_of_joining + $no_of_years; ?></td>
                        <td><?= $no_of_years; ?></td>
                        <td><?= $no_of_sem; ?></td>
                    </tr>
                </tbody>
            </table>
        </section>
        <table>
            <thead>

                <tr>
                    <th>Number of Semesters</th>
                    <th>Subjects</th>
                    <th>Add Subject</th>

                </tr>

            </thead>
            <tbody>
                <?php
                for ($i = 1; $i <= intval($no_of_sem); $i++) : ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td>
                            <select name="" id="">
                                <?php $subjects = $db->query("SELECT * from `batch_subjects` Where `batch_id`= ? and `semester`=?", [$batch_id, $i])->results();
                                if (!empty($subjects)) {
                                    foreach ($subjects as $subject) {
                                        echo "<option> $subject->subject </option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <form action="" id="addsubject<?= $i ?>" method="post">

                                <input style="width: 50%;padding:10px" required type="text" id="subject<?= $i ?>" placeholder="Subject Name">
                                <input class="creditInput" type="number" id="credits<?= $i ?>" placeholder="Credit Points">
                                <input type="hidden" id="semester<?= $i ?>" value="<?= $i; ?>">
                                <input type="hidden" id="batch_id<?= $i ?>" value="<?= $batch_id; ?>">
                                <button type="submit">Add Subject</button>
                            </form>
                        </td>
                    </tr>
                <?php endfor; ?>

            </tbody>
        </table>
    </div>

    <script>
        const popup = document.getElementById('popup');
        const optionForm = document.getElementById('optionForm');
        const cancelbtn = document.getElementById('cancelbtn');
        const onpopbtn = document.querySelector('.onpopbtn');

        // Hide popup on form submit
        popup.classList.add('popup-hidden');


        cancelbtn.addEventListener('click', () => {
            popup.classList.add('popup-hidden');
        })
        onpopbtn.addEventListener('click', () => {
            popup.classList.remove('popup-hidden');
        });

        var year = document.getElementById("year").value;


        async function fetchDpt() {
            let year = document.getElementById("year").value;
            var department_dropdown = document.getElementById("department");
            var response = await fetch("<?= $_ENV["API_URL"]; ?>/backend/api/deptfilter.php?year=" + year);
            var data = await response.json();
            department_dropdown.innerHTML = "<option value=''>Select Department</option>";
            data.forEach((item) => {
                var option = document.createElement("option");
                option.text = item;
                option.value = item;

                department_dropdown.appendChild(option);
            });
        }

        <?php
        for ($i = 1; $i <= $no_of_sem; $i++) :
        ?>
            document.getElementById("addsubject<?= $i ?>").addEventListener("submit", function(e) {
                e.preventDefault();
                let credits = document.getElementById("credits<?= $i ?>").value;
                let subject = document.getElementById("subject<?= $i ?>").value;
                let semester = document.getElementById("semester<?= $i ?>").value;
                let batch_id = document.getElementById("batch_id<?= $i ?>").value;


                const formData = new URLSearchParams();
                formData.append("subject", subject);
                formData.append("semester", semester);
                formData.append("batch_id", batch_id);
                formData.append("credits", credits);
                fetch("backend/addSubject.php", {
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
        <?php
        endfor;
        ?>
    </script>
</body>

</html>