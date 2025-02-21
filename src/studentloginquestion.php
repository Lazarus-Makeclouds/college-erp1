<?php
$db = DB::getInstance();
$department = Input::get("Department");

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $user = $db->get("students_details", ["Email", "=", $user_email])->results();
    
    if ($user) {
        $department = $user[0]->Department; 
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Please log in to view your details.";
    exit();
}


if (Input::exists()) {
    $year = Input::get("year");
    
    
    if (empty($year)) {
        $results = $db->get("questions", ["department", "=", $department], "Year", true)->results();
    } else {
        
        $results = $db->query("SELECT * FROM `questions` WHERE `Year` = ? AND `department` = ? ORDER BY `department` ASC", [$year, $department])->results();
    }
} else {
    
    $results = $db->get("questions", ["department", "=", $department], "Year", true)->results();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/managequestion.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>

<body>
    <h2>Manage Students</h2>
    <section>
        <form action="" method="post">
            <input type="text" id="year" name="year" placeholder="Enter Year">
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
