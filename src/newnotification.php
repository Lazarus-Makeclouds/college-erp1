
<body>
    <div class="manage-notifications">
        <form id="notification-form">
            <label for="notification-text">Notification:</label>
            <textarea id="notification-text" name="notification-text" rows="4" required></textarea>
            <button type="submit">Add Notification</button>
        </form>
        <ul id="notification-list">
            <?php 
            $db = DB::getInstance();
            $results = $db->get_all("notifications");
            foreach ($results->results() as $result): ?>
                <li>
                    <span class="notification-text"><?= escape($result->Notification); ?></span>
                    <input type="hidden" value="<?= $result->NotificationID ?>" class="notification-id">
                    <a href="./backend/deletenotification.php?id=<?= $result->NotificationID ?>" class="delete-btn">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        // Add Notification
        document.getElementById("notification-form").addEventListener("submit", function (e) {
            e.preventDefault();
            const notificationText = document.getElementById("notification-text").value;

            const formData = new URLSearchParams();
            formData.append("notification-text", notificationText);

            fetch("backend/addnotification.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        });

        
        
    </script>
</body>
