<?php
$db = DB::getInstance();
$results = $db->get_all("students_details", "Name")->results();
?>
<ul>
    <a  style="text-decoration: none; color:blue" href="dashboard.php?src=managestudents">Manage Students</a>
    <?php if (isset($_GET['id'])): ?>
        > <a style="text-decoration: none; color:black" href="dashboard.php?src=individualstudents&id=<?= $_GET['id']; ?>">Student Profile</a>
    <?php endif; ?>
    <?php if (isset($_GET['sem'])): ?>
        > <a style="text-decoration: none; color:black" href="hp-works/dashboard.php?src=marks&sem=<?= $_GET['sem']; ?>&id=<?= $_GET['id']; ?>">Marks - Semester <?= $_GET['sem']; ?></a>
    <?php endif; ?>
</ul>
<body>
    <h2>Manage Students</h2>
    <section>
        <form action="" method="POST">
            <input type="text" id="year" name="Year" placeholder="Enter Year">
            <input type="text" id="Department" name="Department" placeholder="Enter Department">
            <input type="submit">
        </form>
    </section>
    <section class="studentsTable">
        <table border="1">
            <caption>
                <!--  -->
            </caption>
            <?php
            if (Session::exists("delete_error")) {
                Session::flash("delete_error");
            }
            ?>
            <thead>
                <tr>
                    <th>Students Image</th>
                    <th scope="col">Students Name</th>
                    <th scope="col">Register Number</th>
                    <th>Admission Number</th>
                    <th>Date of Birth</th>
                    <th>Year of Joining</th>
                    <th>Year of passing out</th>
                    <th scope="col" >Action</th>
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
                            <th><?php if ($result->Image == "null" || $result->Image == '') { ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="imagepreview">
                                        <path fill-rule="evenodd"
                                            d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                <?php } else { ?>
                                    <img src="data:<?php echo $result->filetype; ?>;base64,<?php echo $result->Image; ?>"
                                        class="imagepreview" alt="Profile">
                                <?php } ?>
                            </th>
                            <th scope="row"><?= $result->Name; ?></th>
                            <td><?= $result->Register_number; ?></td>
                            <td><?= $result->Admission_number; ?></td>
                            <td><?= $result->DoB; ?></td>
                            <td><?= $result->Joining_year; ?></td>
                            <td><?= $result->YoP; ?></td>
                            <td class="action">
                                <a href="./dashboard.php?src=individualstudents&id=<?= $result->id; ?>">Manage</a>
                                <a class="delete" href="./backend/studentsprofiledelete.php?id=<?= $result->id; ?>" 
                                    onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                            </td>
                        </tr>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>

    </section>

</body>

</html>