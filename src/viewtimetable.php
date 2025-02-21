<?php
$db = DB::getInstance();
?>

<body>

    <div class="details">
        <h1>Uploaded Timetable Details</h1>
        
        <!-- Table for schedule details -->
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
                $results = $db->get_all("timetable")->results();
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
        <div class="relative">
            <!-- Close Button -->
            <button onclick="closeModal()">&times;</button>

            <!-- Full-Size Image -->
            <img id="modalImage" src="" alt="Full Image">
        </div>
    </div>

    <script>
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
    </script>
</body>

</html>




