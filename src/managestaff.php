<?php
$db = DB::getInstance();

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

    .delete {
        cursor: pointer;
        color: red;
        transition: color 0.3s ease;
    }
</style>

<body>
    <h2>Manage Teachers</h2>
    <section>
        <form action="" method="post" id="filter">
            <input type="int" id="Year" min="1950" max="2050" step="1" name="Year" placeholder="Enter Year Joining">
            <input type="text" id="Name" name="Name" placeholder="Enter Teacher Name">
            <input type="submit">
        </form>
    </section>
    <section class="staffTable">
        <table border="1">
            <caption>
                Teachers Details
            </caption>
            <?php
            if (Session::exists("delete_error")) {
                Session::flash("delete_error");
            }
            ?>
            <thead>
                <tr>
                    <th>Teachers Image</th>
                    <th scope="col">Teacher Name</th>
                    <th>Date of Birth</th>
                    <th>Year of Joining</th>
                    <th scope="col" >Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (Input::exists()):
                    $year = Input::get("Year");
                    $name = Input::get("Name");
                    if (empty($year) & empty($name)) {
                        $results = $db->get_all("teachers_details", "Name", true)->results();
                    } elseif (empty($name)) {
                        $results = $db->get("teachers_details", ["YEAR(`DoJ`)", "=", $year], "Name", true)->results();
                    } elseif (empty($year)) {
                        $results = $db->get("teachers_details", ["Name", "=", $name], "YEAR(`DoJ`)", true)->results();
                    } else {
                        $results = $db->query("SELECT * FROM `teachers_details` WHERE YEAR(`DoJ`) = ? AND `Name` = ? ORDER BY `Name` ASC", [$year, $name])->results();
                    }
                else:
                    $results = $db->get_all("teachers_details", "Name", true)->results();
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
                            <td><?= $result->DoB; ?></td>
                            <td><?= $result->DoJ; ?></td>
                            <td class="action">
                                <a href="./dashboard.php?src=individualstaff&id=<?= $result->id; ?>">Manage</a>
                                <a class="delete" href="./backend/staffdelete.php?id=<?= $result->id; ?>"
                                onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                            </td>
                        </tr>
                <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>

    </section>

</body>
<script>
    // document.getElementById('filter').addEventListener('submit', function(e) {
    //     e.preventDefault();
    //     let year = document.getElementById('Year').value;
    //     let name = document.getElementById('Name').value;

    // });
</script>

</html>