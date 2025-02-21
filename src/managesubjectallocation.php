<?php
require_once "./core/init.php";
ob_start();
// Include necessary files
$db = DB::getInstance();

if (Input::exists()):
    $year = Input::get("Year");
    $name = Input::get("Name");
    
    // Check if both filters are empty, only apply one condition if not
    if (empty($year) && empty($name)) {
        $results = $db->get_all("subject_allocation", "Name", true)->results();
    } elseif (empty($name)) {
        $results = $db->get("subject_allocation", ["YEAR(`allocated_at`)", "=", $year], "Name", true)->results();
    } elseif (empty($year)) {
        $results = $db->get("subject_allocation", ["Name", "=", $name], "YEAR(`allocated_at`)", true)->results();
    } else {
        // Modify this query to join with batch_subject and select subject_id
        $results = $db->query("
            SELECT sa.*, bs.id 
            FROM `subject_allocation` sa
            LEFT JOIN `batch_subjects` bs ON sa.subject = bs.name 
            WHERE YEAR(sa.allocated_at) = ? AND sa.Name = ?
            ORDER BY sa.Name ASC", 
            [$year, $name]
        )->results();
    }
else:
    $results = $db->get_all("subject_allocation", "Name", true)->results();
endif;
?>

<style>
    * {
        text-decoration: none;
        text-decoration-color: blue;
    }

    .action {
        display: flex;
        gap: 10px;
        align-items: center;
        border: none !important;
        cursor: pointer;
        justify-content: center;
    }

    /* Modal Styles */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1000;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.5);
        /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        /* White background */
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        /* Border color */
        width: 80%;
        /* Could be more or less, depending on screen size */
        max-width: 600px;
        /* Maximum width */
        border-radius: 8px;
        /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Shadow effect */
    }

    .close {
        color: #aaa;
        /* Close button color */
        float: right;
        /* Align to the right */
        font-size: 28px;
        /* Font size */
        font-weight: bold;
        /* Bold text */
    }

    .close:hover,
    .close:focus {
        color: black;
        /* Change color on hover */
        text-decoration: none;
        /* Remove underline */
        cursor: pointer;
        /* Pointer cursor */
    }

    h2 {
        margin-top: 0;
        /* Remove top margin */
    }

    label {
        display: block;
        /* Block display for labels */
        margin: 10px 0 5px;
        /* Margin for spacing */
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        /* Full width */
        padding: 10px;
        /* Padding for input */
        margin-bottom: 15px;
        /* Space below inputs */
        border: 1px solid #ccc;
        /* Border color */
        border-radius: 4px;
        /* Rounded corners */
        box-sizing: border-box;
        /* Include padding and border in width */
    }

    button {
        background-color: #4CAF50;
        /* Green background */
        color: white;
        /* White text */
        padding: 10px 15px;
        /* Padding for buttons */
        border: none;
        /* No border */
        border-radius: 4px;
        /* Rounded corners */
        cursor: pointer;
        /* Pointer cursor */
        margin-right: 10px;
        /* Space between buttons */
    }

    button:hover {
        background-color: #45a049;
        /* Darker green on hover */
    }

    button[type="button"] {
        background-color: #f44336;
        /* Red background for cancel */
    }

    button[type="button"]:hover {
        background-color: #e53935;
        /* Darker red on hover */
    }
    /* Section Styles */
.staffTable {
    background-color: #fff; /* White background */
    padding: 20px; /* Padding around the content */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    width: 100%; /* Full width */
    max-width: 1200px; /* Maximum width */
    margin: 20px auto; /* Center the section */
}

/* Table Styles */
.staffTable table {
    width: 100%; /* Full width of the section */
    border-collapse: collapse; /* Merge borders */
}

.staffTable th, .staffTable td {
    padding: 10px; /* Padding for cells */
    border: 1px solid #ddd; /* Light border */
    text-align: left; /* Left-align text */
}



.staffTable tr:hover {
    background-color: #f1f1f1; /* Row hover effect */
}
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
                    <th>Subject ID</th>
                    <th>Subject</th>
                    <th>Allocated At</th>
                    <th>Updated</th>
                    <th scope="col">Action</th>
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
                            <td><?= $result->subject_id; ?></td>
                            <td><?= $result->subject; ?></td>
                            <td><?= $result->allocated_at; ?></td>
                            <td><?= $result->updated_at; ?></td>
                            <td class="action">
                                <button type="button" class="edit-btn" onclick="openModal(<?= $result->id; ?>, '<?= $result->Name; ?>', <?= $result->Year; ?>, '<?= $result->Department; ?>', <?= $result->Sem; ?>, '<?= $result->subject; ?>')">Edit</button>
                                <form action="dashboard.php?src=deleteallocation" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $result->id; ?>">
                                    <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this allocation?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                <?php endforeach;
                endif; ?>

            </tbody>
        </table>
    </section>

    <!-- Modal for Editing -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Subject Allocation</h2>
            <form id="editForm" method="post" action="dashboard.php?src=editallocation">
                <input type="hidden" name="id" id="editId">
                <label for="editName">Name:</label>
                <input type="text" name="Name" id="editName" required>
                <label for="editYear">Year:</label>
                <input type="number " name="Year" id="editYear" required>
                <label for="editDepartment">Department:</label>
                <input type="text" name="Department" id="editDepartment" required>
                <label for="editSem">Semester:</label>
                <input type="number" name="Sem" id="editSem" required>
                <label for="editSubject">Subject:</label>
                <input type="text" name="subject" id="editSubject" required>
                <button type="submit">Save</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, name, year, department, sem, subject) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editYear').value = year;
            document.getElementById('editDepartment').value = department;
            document.getElementById('editSem').value = sem;
            document.getElementById('editSubject').value = subject;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>
<?php
ob_end_flush();
?>