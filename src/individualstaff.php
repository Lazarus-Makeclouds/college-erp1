<?php
if (Input::exists("get")):
    $db = DB::getInstance();
    $id = Input::get("id");
    $results = $db->get("teachers_details", ["id", "=", $id])->results();
else:
    Redirect::to("./manageteacher.php");
endif;
?>
<body>

<style>
    .profileimage {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>
<section>

    <?php foreach ($results as $result): ?>
        <h2><?= $result->Name; ?> Profile</h2>
    <?php endforeach; ?>

    <div class="button-container">
        <button onclick="downloadCSV()">Download CSV</button>
        <button onclick="downloadExcel()">Download Excel</button>
        <button onclick="downloadPDF()">Download PDF</button>
    </div>

    <table id="stafftable">
        <thead>
            <tr>
                <th>Profile</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td>Teacher Image</td>
                    <td>
                        <?php if ($result->Image == "null" || $result->Image == '') { ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="h-10 rounded-full pulse">
                                <path fill-rule="evenodd"
                                      d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        <?php } else { ?>
                            <img src="data:<?= $result->filetype; ?>;base64,<?= $result->Image; ?>" class="profileimage" alt="Profile">
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>Teacher Name</td>
                    <td><?= $result->Name; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= $result->Email; ?></td>
                </tr>
                <tr>
                    <td>Nationality</td>
                    <td><?= $result->Nationality; ?></td>
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
                    <td>Date of Birth</td>
                    <td><?= $result->DoB; ?></td>
                </tr>
                <tr>
                    <td>Residential Address</td>
                    <td><?= $result->Residential_Address; ?></td>
                </tr>
                <tr>
                    <td>Level</td>
                    <td><?= $result->Level; ?></td>
                </tr>
                <tr>
                    <td>Major</td>
                    <td><?= $result->Major; ?></td>
                </tr>
                <tr>
                    <td>Field of Study</td>
                    <td><?= $result->FOS; ?></td>
                </tr>
                <tr>
                    <td>Institute/University</td>
                    <td><?= $result->InstitudeUniversity; ?></td>
                </tr>
                <tr>
                    <td>Graduation Year</td>
                    <td><?= $result->Graduation; ?></td>
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
                    <td>Mobile Number</td>
                    <td><?= $result->Mobile_no; ?></td>
                </tr>
                <tr>
                    <td>Institution Name</td>
                    <td><?= $result->InstitutionName; ?></td>
                </tr>
                <tr>
                    <td>Position Title</td>
                    <td><?= $result->PositionTitle; ?></td>
                </tr>
                <tr>
                    <td>Position Level</td>
                    <td><?= $result->PositionLevel; ?></td>
                </tr>
                <tr>
                    <td>Specialization</td>
                    <td><?= $result->Specialization; ?></td>
                </tr>
                <tr>
                    <td>Industry</td>
                    <td><?= $result->Industry; ?></td>
                </tr>
                <tr>
                    <td>Date of Joining</td>
                    <td><?= $result->DoJ; ?></td>
                </tr>
                <tr>
                    <td>Skills</td>
                    <td><?= $result->Skill; ?></td>
                </tr>
                <tr>
                    <td>Languages</td>
                    <td><?= $result->Language; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

    <script>
        // CSV Download Function
        function downloadCSV() {
            const table = document.getElementById('stafftable');
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
            link.download = 'staff_data.csv';
            link.click();
        }

        // Excel Download Function
        function downloadExcel() {
            const table = document.getElementById('stafftable');
            const worksheet = XLSX.utils.table_to_sheet(table);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Students');
            XLSX.writeFile(workbook, 'staff_data.xlsx');
        }

        // PDF Download Function
        async function downloadPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            doc.text("Staff Data", 14, 10);
            doc.autoTable({
                html: '#stafftable',
                startY: 20,
                theme: 'grid',
                headStyles: {
                    fillColor: [41, 128, 185]
                }
            });
            doc.save('staff_data.pdf');
        }
    </script>
</body>


</html>