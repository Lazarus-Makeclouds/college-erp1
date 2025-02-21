<?php
$db = DB::getInstance();
if (Input::exists()):
    $year = Input::get("year");
    $department = Input::get("Department");
    if (empty($year) & empty($department)) {
        $results = $db->get_all("questions", "Year", true)->results();
    } elseif (empty($department)) {
        $results = $db->get("questions", ["Year", "=", $year], "department", true)->results();
    } elseif (empty($year)) {
        $results = $db->get("questions", ["department", "=", $department], "Year", true)->results();
    } else {
        $results = $db->query("SELECT * FROM `questions` WHERE `Year` = ? AND `department` = ? ORDER BY `department` ASC", [$year, $department])->results();
    }
else:
    $results = $db->get_all("questions", "Department", true)->results();
endif;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/managequestion.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>

<body>
    <h2>Manage Students</h2>
    <section>
        <form action="" method="post">
            <input type="text" id="year" name="year" placeholder="Enter Year">
            <input type="text" id="Department" name="Department" placeholder="Enter Department">
            <input type="submit">
        </form>
    </section>
    <section>
        <table border="1">
            <caption>
                Questions
            </caption>
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Department</th>
                    <th>Subject</th>
                    <th>Subject Code</th>
                    <th>Semester</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?= $result->Year; ?></td>
                        <td><?= $result->department; ?></td>
                        <td><?= $result->subject; ?></td>
                        <td><?= $result->subject_code; ?></td>
                        <td><?= $result->semester; ?></td>
                        <td class="action">
                            <a href="./downloadquestion.php?id=<?= $result->id; ?>">Download</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </section>

</body>

</html>