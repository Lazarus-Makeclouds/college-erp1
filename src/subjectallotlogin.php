<?php
require_once "./core/init.php";
ob_start();
// Include necessary files
$db = DB::getInstance();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    die("You must be logged in to view this page.");
}

// Get the logged-in user's email
$user_email = $_SESSION['user_email'];

// Fetch the teacher's details based on their email
$staff_results = $db->get("teachers_details", ["Email", "=", $user_email])->results();
if ($staff_results) {
    $staff = $staff_results[0];
    $teacher_name = $staff->Name; // Assuming 'Name' is the field in teachers_details

    // Fetch subject allocations for the logged-in teacher using the teacher's name
    $results = $db->get("subject_allocation", ["Name", "=", $teacher_name])->results();
} else {
    $results = [];
}
?>

<style>
    /* Your existing styles here */
</style>

<body>
    <h2>Manage Subject Allocation</h2>

    <!-- Table Section -->
    <section class="staffTable">
        <table border="1">
            <caption>Subject Allocation Details</caption>

            <?php
            // Display any error messages
            if (Session::exists("delete_error")) {
                Session::flash("delete_error");
            }
            ?>

            <thead>
                <tr>
                    <th scope="col">Teacher Name</th>
                    <th>Year of Allocation</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>Allocated At</th>
                    <th>Updated</th>
                   
                </tr>
            </thead>

            <tbody>
                <?php
                if (empty($results)):
                    echo "<tr><td colspan='8'>No Results Found.</td></tr>";
                else:
                    foreach ($results as $result): ?>
                        <tr>
                            <th scope="row"><?= $result->Name; ?></th>
                            <td><?= $result->Year; ?></td>
                            <td><?= $result->Department; ?></td>
                            <td><?= $result->Sem; ?></td>
                            <td><?= $result->subject; ?></td>
                            <td><?= $result->allocated_at; ?></td>
                            <td><?= $result->updated_at; ?></td>
                            
                        </tr>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>
    </section>

   
    
</body>

</html>
<?php
ob_end_flush();
?>