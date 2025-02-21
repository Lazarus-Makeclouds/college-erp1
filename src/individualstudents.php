<?php
if (Input::exists("get")):
    $db = DB::getInstance();
    $id = Input::get("id");
    $results = $db->get("students_details", ["id", "=", $id])->results();
    $numberOfSemesters = $results[0]->Number_of_semesters;
    $subject = Input::get("subject");
else:
    Redirect::to("./admin.php?admin=managestudents");
endif;
?>
<style>
    .profileimage {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
    }
    
</style>
<ul>
    <a style="text-decoration: none; color:black" href="dashboard.php?src=managestudents">Manage Students</a>
    <?php if (isset($_GET['id'])): ?>
        > <a style="text-decoration: none; color:blue" href="dashboard.php?src=individualstudents&id=<?= $_GET['id']; ?>">Student Profile</a>
    <?php endif; ?>
    <?php if (isset($_GET['sem'])): ?>
        > <a style="text-decoration: none; color:black" href="hp-works/dashboard.php?src=marks&sem=<?= $_GET['sem']; ?>&id=<?= $_GET['id']; ?>">Marks - Semester <?= $_GET['sem']; ?></a>
    <?php endif; ?>
</ul>
<body>
    <section>
        <?php foreach ($results as $result): ?>
            <h2><?= $result->Name; ?> Profile</h2>
        <?php endforeach; ?>

        <div class="button-container">
            <button onclick="downloadCSV('studentTable')">Download CSV</button>
            <button onclick="downloadExcel('studentTable')">Download Excel</button>
            <button onclick="downloadPDF('studentTable')">Download PDF</button>
        </div>
        <table id="studentTable">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Profile</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td>Student Image</td>
                        <td>
                            <?php if ($result->Image == "null" || $result->Image == '') { ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 rounded-full pulse">
                                    <path fill-rule="evenodd" d="..." clip-rule="evenodd" />
                                </svg>
                            <?php } else { ?>
                                <img src="data:<?php echo $result->filetype; ?>;base64,<?php echo $result->Image; ?>" class="profileimage" alt="Profile">
                            <?php } ?>
                        </td> </tr>
                        <tr>
                            <td>Student Name</td>
                            <td><?= $result->Name; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?= $result->Email; ?></td>
                        </tr>
                        <tr>
                            <td>Register Number</td>
                            <td><?= $result->Register_number; ?></td>
                        </tr>
                        <tr>
                            <td>Admission Number</td>
                            <td><?= $result->Admission_number; ?></td>
                        </tr>
                        <tr>
                            <td>Date of Birth</td>
                            <td><?= $result->DoB; ?></td>
                        </tr>
                        <tr>
                            <td>Year of Joining</td>
                            <td><?= $result->Joining_year; ?></td>
                        </tr>
                        <tr>
                            <td>Year of Passing Out</td>
                            <td><?= $result->YoP; ?></td>
                        </tr>
                        <tr>
                            <td>Father's Name</td>
                            <td><?= $result->Father_Name; ?></td>
                        </tr>
                        <tr>
                            <td>Mother's Name</td>
                            <td><?= $result->Mother_Name; ?></td>
                        </tr>
                        <tr>
                            <td>Mother Tongue</td>
                            <td><?= $result->Mother_Tongue; ?></td>
                        </tr>
                        <tr>
                            <td>Marital Status</td>
                            <td><?= $result->Marital_Status; ?></td>
                        </tr>
                        <tr>
                            <td>Husband's Name</td>
                            <td><?= $result->Husband_Name; ?></td>
                        </tr>
                        <tr>
                            <td>Blood Group</td>
                            <td><?= $result->Blood_group; ?></td>
                        </tr>
                        <tr>
                            <td>Residential Address</td>
                            <td><?= $result->Residential_Address; ?></td>
                        </tr>
                        <tr>
                            <td>Aadhar Number</td>
                            <td><?= $result->Aadhar_Number; ?></td>
                        </tr>
                        <tr>
                            <td>ABC ID</td>
                            <td><?= $result->AbcId; ?></td>
                        </tr>
                        <tr>
                            <td>APAAR</td>
                            <td><?= $result->Apaar; ?></td>
                        </tr>
                        <tr>
                            <td>Department</td>
                            <td><?= $result->Department; ?></td>
                        </tr>
                        <tr>
                            <td>Batch</td>
                            <td><?= $result->Batch; ?></td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td><?= $result->Branch; ?></td>
                        </tr>
                        <tr>
                            <td>10th Marks</td>
                            <td><?= $result->Mark10; ?></td>
                        </tr>
                        <tr>
                            <td>12th Marks</td>
                            <td><?= $result->Mark12; ?></td>
                        </tr>
                        <tr>
                            <td>Community</td>
                            <td><?= $result->Community; ?></td>
                        </tr>
                        <tr>
                            <td>Religion</td>
                            <td><?= $result->Religion; ?></td>
                        </tr>
                        <tr>
                            <td>Nationality</td>
                            <td><?= $result->Nationality; ?></td>
                        </tr>
                        <tr>
                            <td>Bank Name</td>
                            <td><?= $result->BankName; ?></td>
                        </tr>
                        <tr>
                            <td>IFSC Code</td>
                            <td><?= $result->Ifsc; ?></td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td><?= $result->AccountNumber; ?></td>
                        </tr>
                        <tr>
                            <td>Mobile Number</td>
                            <td><?= $result->Mobile_no; ?></td>
                        </tr>
                        <tr>
                            <td>TC Issued Date</td>
                            <td><?= $result->TC_issue; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="semester-selection">
    <label for="semesters">Select Semester:</label>
    <div class="semester-selection">
    <div>
        <?php for ($i = 1; $i <= $numberOfSemesters; $i++): ?>
            <button class="semester-button" onclick="openMarksPage(<?= htmlspecialchars($id); ?>, <?= $i; ?>)">Semester <?= $i; ?></button>
        <?php endfor; ?>
    </div>
</div>

<script>
    function openMarksPage(id, semester) {
        if (semester) {
            // Append a timestamp to the URL to force a fresh load
            var timestamp = new Date().getTime();
            var url = `./dashboard.php?src=marks&sem=${semester}&id=${id}&timestamp=${timestamp}`;

            window.location.href = url;
        }
    }

            function downloadCSV(tableId) {
                const table = document.getElementById(tableId);
                let csvContent = "";

                for (let row of table.rows) {
                    let rowData = [];
                    for (let cell of row.cells) {
                        rowData.push(cell.innerText);
                 }
                    csvContent += rowData.join(",") + "\n";
                }

                const blob = new Blob([csvContent], {
                    type: 'text/csv'
                });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `${tableId}_data.csv`;
                link.click();
            }

            function downloadExcel(tableId) {
                const table = document.getElementById(tableId);
                const worksheet = XLSX.utils.table_to_sheet(table);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, tableId);
                XLSX.writeFile(workbook, `${tableId}_data.xlsx`);
            }

            async function downloadPDF(tableId) {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                doc.autoTable({
                    html: `#${tableId}`,
                    startY: 20,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [41, 128, 185]
                    }
                });
                doc.save(`${tableId}_data.pdf`);
            }
        </script>
    </section>
</body>
</html>