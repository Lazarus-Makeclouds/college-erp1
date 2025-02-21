<?php


$db = DB::getInstance();


if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];


    $staff_results = $db->get("teachers_details", ["Email", "=", $user_email])->results();


    if ($staff_results) {
        $staff = $staff_results[0];
        $teacher_results = $db->get("teachers_details", ["Email", "=", $user_email])->results(); // Query for the teacher data using the logged-in email


        if ($teacher_results) {

            $teacher = $teacher_results[0];
?>
            <h2>Manage Teacher: <?= $teacher->Name; ?></h2>
            <section class="staffTable">
                <table border="1">
                    <caption>
                        Teacher Details
                    </caption>
                    <style>
                        thead th {
                            background-color: #3498db;
                            color: white;
                            padding: 10px;
                            text-align: left;
                        }

                        /* Table Data Styling */
                        thead td {
                            padding: 10px;
                            text-align: left;
                            border-bottom: 1px solid #ddd;
                        }
                    </style>
                    <thead>
                        <tr>
                            <th>Teacher Image</th>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Year of Joining</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>
                                <?php if ($teacher->Image == "null" || $teacher->Image == '') { ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="imagepreview">
                                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                                    </svg>
                                <?php } else { ?>
                                    <img src="data:<?php echo $teacher->filetype; ?>;base64,<?php echo $teacher->Image; ?>" class="imagepreview" alt="Profile">
                                <?php } ?>
                            </th>
                            <th><?= $teacher->Name; ?></th>
                            <td><?= $teacher->DoB; ?></td>
                            <td><?= $teacher->DoJ; ?></td>
                            <td class="action">
                                <a href="./dashboard.php?src=individualstaff&id=<?= $teacher->id; ?>">Manage</a>



                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
<?php
        } else {
            echo "Teacher details not found for this user.";
        }
    } else {
        echo "Staff details not found.";
    }
} else {
    echo "Please log in to view your details.";
    exit();
}
?>