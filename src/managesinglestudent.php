<?php

$db = DB::getInstance();


if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    
   
    $results = $db->get("students_details", ["Email", "=", $user_email])->results();
} else {
    
    echo "Please log in to view your details.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<ul>
            <a style="text-decoration: none; color:blue" href="dashboard.php?src=managesinglestudent">Manage Students</a>
            
        </ul>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <style>
       /* General Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    color: #333;
}

/* Title Styling */


/* Table Styling */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #ffffff;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}

/* Table Header Styling */
thead th {
    background-color:#3498db;
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


/* Action Column Styling */
.action a {
    color: #007BFF;
    text-decoration: none;
    font-weight: bold;
}



/* Students Image Styling */
.imagepreview {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

/* Student Table Section */
.studentsTable {
    margin: 20px auto;
    width: 80%;
    padding: 10px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Input Fields and Form Styling (for filters or other forms) */
input, select {
    padding: 8px;
    margin: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

/* Flash Message Styling */
.flash-message {
    text-align: center;
    margin-top: 20px;
    color: red;
    font-weight: bold;
}

/* Responsive Design */
@media (max-width: 768px) {
    table {
        width: 100%;
        font-size: 14px;
    }

    .studentsTable {
        width: 100%;
    }

    th, td {
        padding: 8px;
    }
}

    </style>
</head>

<body>

    
    <h2>Manage Students</h2>
    
    
    
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
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (Input::exists()):
                    $year = Input::get("Year");
                    $department = Input::get("Department");
                    if (empty($year) && empty($department)) {
                        $results = $db->get_all("students_details", "Name", true)->results();
                    } elseif (empty($department)) {
                        $results = $db->get("students_details", ["YEAR(`Joining_year`)", "=", $year], "Department", true)->results();
                    } elseif (empty($year)) {
                        $results = $db->get("students_details", ["Department", "=", $department], "YEAR(`Joining_year`)", true)->results();
                    } else {
                        $results = $db->query("SELECT * FROM `students_details` WHERE YEAR(`Joining_year`) = ? AND `Department` = ? ORDER BY `Department` ASC", [$year, $department])->results();
                    }
                else:
                  
                    $results = $db->get("students_details", ["Email", "=", $user_email])->results();
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
                                <a href="./dashboard.php?src=individualstudentlogin&id=<?= $result->id; ?>">Manage</a>
                                
                            </td>
                        </tr>
                    <?php endforeach;
                endif;
                ?>
            </tbody>
        </table>
    </section>

</body>

</html>
