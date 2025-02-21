<?php
$db = DB::getInstance();

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
        $results = $db->get("timetable", ["department", "=", $department], "Year", true)->results();
    } else {
        $results = $db->query("SELECT * FROM `timetable` WHERE `Year` = ? AND `department` = ? ORDER BY `department` ASC", [$year, $department])->results();
    }
} else {
    $results = $db->get("timetable", ["department", "=", $department], "Year", true)->results();
}
?>

<body>

    <div class="details">
        <h1>Uploaded Timetable Details</h1>
        
        
        <table>
            <thead>
                <tr>
                    <th>Joining Year</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($results as $result):
                    echo "<tr>";
                    echo "<td>{$result->Year}</td>";
                    echo "<td>{$result->department}</td>";
                    echo "<td>{$result->semester}</td>";
                    echo "<td>{$result->from}</td>";
                    echo "<td>{$result->to}</td>";
                    echo "<td><button onclick=\"openModal('data:{$result->filetype};base64,{$result->Image}')\">View</button></td>";
                    echo "</tr>";
                endforeach;
                ?>
            </tbody>
        </table>
    </div>

    
    <div id="imageModal" class="hidden">
        <div class="modal-content">
            
            <button id="closeButton" onclick="closeModal()">×</button>

           
            <img id="modalImage" src="">
        </div>
    </div>

    <script>
       
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').style.display = 'block'; 
        }

        
        function closeModal() {
            document.getElementById('imageModal').style.display = 'none'; 
        }

        
        window.onclick = function(event) {
            if (event.target === document.getElementById('imageModal')) {
                closeModal();
            }
        }
    </script>

    <style>
        
       

        .modal-content {
            position: relative;
            margin: auto;
            padding: 20px;
            width: 80%;
            max-width: 700px;
        }

        #modalImage {
            width: 100%;
            height: auto;
        }

        #closeButton {
            position: absolute;
            top: 10px;
            right: 25px;
            color: white;
            font-size: 30px;
            background: transparent;
            border: none;
            cursor: pointer;
        }
    </style>
</body>
