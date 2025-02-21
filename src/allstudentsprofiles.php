<?php
$db = DB::getInstance();
if (Input::exists("get")):
    $results = $db->get_all("students_details");
endif;
?>

<body>
    <section class="parent-section-form">
        <form action="" method="POST">
            <input type="text" id="year" name="Year" placeholder="Enter Year">
            <input type="text" id="Department" name="Department" placeholder="Enter Department">
            <input type="submit">
        </form>
    </section>

    <section>
    <div class="table-wrapper">
        <button id="downloadCSV">Download CSV</button>
        <button id="downloadExcel">Download Excel</button>

        <table id="studentTable">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Register Number</th>
                    <th>Admission Number</th>
                    <th>Date of Birth</th>
                    <th>Year of Joining</th>
                    <th>Year of Passing Out</th>
                    <th>Father's Name</th>
                    <th>Mother's Name</th>
                    <th>Mother Tongue</th>
                    <th>Marital Status</th>
                    <th>Husband's Name</th>
                    <th>Blood Group</th>
                    <th>Residential Address</th>
                    <th>Aadhar Number</th>
                    <th>ABC ID</th>
                    <th>APAAR</th>
                    <th>Department</th>
                    <th>Batch</th>
                    <th>Branch</th>
                    <th>10th Marks</th>
                    <th>12th Marks</th>
                    <th>Community</th>
                    <th>Religion</th>
                    <th>Nationality</th>
                    <th>Bank Name</th>
                    <th>IFSC Code</th>
                    <th>Account Number</th>
                    <th>Mobile Number</th>
                    <th>TC Issued Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (Input::exists()):
                    $year = Input::get("Year");
                    $department = Input::get("Department");
                    if (empty($year) & empty($department)) {
                        $results = $db->get_all("students_details", "Name", true)->results();
                    } elseif (empty($department)) {
                        $results = $db->get("students_details", ["YEAR(`Joining_year`)", "=", $year], "Department", true)->results();
                    } elseif (empty($year)) {
                        $results = $db->get("students_details", ["Department", "=", $department], "YEAR(`Joining_year`)", true)->results();
                    } else {
                        $results = $db->query("SELECT * FROM `students_details` WHERE YEAR(`Joining_year`) = ? AND `Department` = ? ORDER BY `Department` ASC", [$year, $department])->results();
                    }
                else:
                    $results = $db->get_all("students_details", "Department", true)->results();
                endif;
                if (empty($results)):
                    echo "<tr><td colspan='5'>No Results Found.</td></tr>";
                else:
                    foreach ($results as $result): ?>
                        <tr>
                            <td>
                                <?php if ($result->Image == "null" || $result->Image == '') { ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 rounded-full pulse">
                                        <path fill-rule="evenodd" d="..." clip-rule="evenodd" />
                                    </svg>
                                <?php } else { ?>
                                    <img src="data:<?php echo $result->filetype; ?>;base64,<?php echo $result->Image; ?>" class="profileimage" alt="Profile">
                                <?php } ?>
                            </td>
                            <td><?= $result->Name; ?></td>
                            <td><?= $result->Email; ?></td>
                            <td><?= $result->Register_number; ?></td>
                            <td><?= $result->Admission_number; ?></td>
                            <td><?= $result->DoB; ?></td>
                            <td><?= $result->Joining_year; ?></td>
                            <td><?= $result->YoP; ?></td>
                            <td><?= $result->Father_Name; ?></td>
                            <td><?= $result->Mother_Name; ?></td>
                            <td><?= $result->Mother_Tongue; ?></td>
                            <td><?= $result->Marital_Status; ?></td>
                            <td><?= $result->Husband_Name; ?></td>
                            <td><?= $result->Blood_group; ?></td>
                            <td><?= $result->Residential_Address; ?></td>
                            <td><?= $result->Aadhar_Number; ?></td>
                            <td><?= $result->AbcId; ?></td>
                            <td><?= $result->Apaar; ?></td>
                            <td><?= $result->Department; ?></td>
                            <td><?= $result->Batch; ?></td>
                            <td><?= $result->Branch; ?></td>
                            <td><?= $result->Mark10; ?></td>
                            <td><?= $result->Mark12; ?></td>
                            <td><?= $result->Community; ?></td>
                            <td><?= $result->Religion; ?></td>
                            <td><?= $result->Nationality; ?></td>
                            <td><?= $result->BankName; ?></td>
                            <td><?= $result->Ifsc; ?></td>
                            <td><?= $result->AccountNumber; ?></td>
                            <td><?= $result->Mobile_no; ?></td>
                            <td><?= $result->TC_issue; ?></td>
                        </tr>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>
        </div>
    </section>

    <script>
        // Download CSV
        document.getElementById('downloadCSV').addEventListener('click', function() {
            let csvContent = '';
            const rows = document.querySelectorAll('#studentTable tr');
            rows.forEach(row => {
                const cols = row.querySelectorAll('td, th');
                const rowData = Array.from(cols).map(col => col.innerText.replace(/,/g, ''));
                csvContent += rowData.join(',') + '\n';
            });
            const blob = new Blob([csvContent], {
                type: 'text/csv'
            });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'students.csv';
            a.click();
        });

        // Download Excel
        document.getElementById('downloadExcel').addEventListener('click', function() {
            const table = document.getElementById('studentTable');
            const workbook = XLSX.utils.table_to_book(table);
            XLSX.writeFile(workbook, 'students.xlsx');
        });
    </script>
</body>

</html>