<!DOCTYPE html>
<html lang="en">
<?php
// Fetch notifications from the database
$db = DB::getInstance()->get_all("notifications");
$notifications = $db->results(); // Renamed to avoid confusion
$count = $db->count();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Sidebar</title>
    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" />

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f6fa;
        }

        .container {
            display: flex;
            flex: 1;
            width: 100%;
        }

        .sidebar {
            height: 100vh;
            width: 300px;
            background: rgb(219, 223, 224);
            color: black;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: fixed;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar h2 {
            color: black;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            padding: 15px 0;
            background-color: rgba(0, 0, 0, 0.1);
            margin: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar a {
            text-decoration: none;
            color: black;
            padding: 12px 20px;
            font-size: 15px;
            display: block;
            transition: background-color 0.3s, padding-left 0.3s;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar a:hover {
            background-color: rgba(20, 19, 19, 0.1);
            padding-left: 25px;
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .main-content {
            padding: 20px;
            margin: 50px auto;
            flex: auto;
            margin-left: 300px;
            width: calc(100% - 300px);
        }

        .content-section {
            display: none;
        }

        .content-section:target {
            display: block;
        }

        #home {
            display: block;
        }

        h2 {
            font-size: 24px;
            color: #2c3e50;
        }

        /* Main Content Styling */
        .content-card {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Scrollbar Styles for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #2c3e50;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #34495e;
            border-radius: 3px;
        }

        .menuBtn {
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 200px;
                display: none;
            }

            .sidebar a {
                font-size: 14px;
                padding: 8px 10px;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                margin: 50px auto;
            }

            .menuBtn {
                display: block;
            }

            .notificationDropdown {
                display: none;
                position: absolute;
                top: 10;
                background-color: #2c3e50;
                border-radius: 12px;
                padding: 5px;
                color: whitesmoke;
                margin-top: 40px;
                margin-right: 20px;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: space-around;
            }

            .sidebar h2 {
                display: none;
            }

            .sidebar a {
                padding: 10px;
                text-align: center;
                flex: 1 0 33%;
                border: none;
                border-right: 1px solid rgba(255, 255, 255, 0.2);
            }

            .sidebar a:nth-child(3n) {
                border-right: none;
            }

            .main-content {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .sidebar a {
                font-size: 12px;
                padding: 8px;
            }

            .main-content {
                padding: 10px;
            }
        }

        .logout {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
            margin-right: 10px;
            gap: 10px;
        }

        .logout-btn {
            padding: 5px;
            border-radius: 10px;
            background-color: #3498db;
            color: #ecf0f1;
            text-decoration: none;
            font-size: small;
        }

        .profile-svg {
            margin: auto 0;
            margin-right: 5px;
            height: 24px;
            width: 24px;
        }

        #notificationBtn {
            margin: auto 0;
            height: 24px;
            cursor: pointer;
            width: 24px;
        }

        .logout-btn:hover {
            background-color: #2c3e50;
        }

        nav {
            background-color: whitesmoke;
            position: fixed;
            width: 100%;
            height: 50px;
        }

        .sidebar {
            scrollbar-width: thin;
        }

        .sidebar::-webkit-scrollbar {
            width: 3px;
        }

        .last-content {
            padding-bottom: 50px;

        }

        .menuBtn {
            height: 24px;
            width: 24px;
            cursor: pointer;
        }

        .show-sidebar {
            display: block;
        }

        .notificationDropdown {
            display: none;
            position: absolute;
            top: 10;
            background-color: #2c3e50;
            border-radius: 12px;
            padding: 5px;
            color: whitesmoke;
            margin-top: 40px;
            margin-right: 60px;
            padding-right: 35px;
        }

        .notificationDropdownActive {
            display: block;
        }
    </style>
</head>

<body>

    <script>
        function toggleMenu() {
            const sidebar = document.querySelector(".sidebar");
            sidebar.classList.toggle("show-sidebar")
        }
    </script>

    <script>
        window.addEventListener('beforeunload', () => {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                localStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                const scrollPosition = localStorage.getItem('sidebarScrollPosition');
                if (scrollPosition) {
                    sidebar.scrollTop = parseInt(scrollPosition, 10);
                }
            }
        });
        // const notificationDropdown = document.getElementsByClassName("notificationDropdown");
        // const notificationBtn = document.getElementById('notificationBtn')

        // notificationBtn.onclick = notificationDropdown.addclassList('notificationDropdown.active')

        function toggleNotification() {
            const dropdown = document.querySelector(".notificationDropdown");
            dropdown.classList.toggle("notificationDropdownActive");
        }
    </script>

    <nav>
        <?php
        $current_page = $_GET['src'] ?? '';
        ?>
        <div class="logout">
            <div onclick="toggleMenu()">
                <svg class="menuBtn" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </div>

            <?php
            if ($user->hasPermission("staff")):
            ?>
                <svg class="profile-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                        <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10S17.523 2 12 2" />
                        <path d="M4.271 18.346S6.5 15.5 12 15.5s7.73 2.846 7.73 2.846M12 12a3 3 0 1 0 0-6a3 3 0 0 0 0 6" />
                    </g>
                </svg>
                <div id="notificationBtn" onclick="toggleNotification()"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <g fill="none" fill-rule="evenodd">
                            <path d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z" />
                            <path fill="#000" d="M5 9a7 7 0 0 1 14 0v3.764l1.822 3.644A1.1 1.1 0 0 1 19.838 18h-3.964a4.002 4.002 0 0 1-7.748 0H4.162a1.1 1.1 0 0 1-.984-1.592L5 12.764zm5.268 9a2 2 0 0 0 3.464 0zM12 4a5 5 0 0 0-5 5v3.764a2 2 0 0 1-.211.894L5.619 16h12.763l-1.17-2.342a2 2 0 0 1-.212-.894V9a5 5 0 0 0-5-5" />
                        </g>
                    </svg>
                </div>

                <div class="notificationDropdown">
                    <ul>
                        <li>Notification working Model</li>
                        <?php if (empty($notifications)): ?>
                            <li>No notifications available.</li>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                                <li><?php echo htmlspecialchars($notification->Notification); ?></li>

                                <span class="text-xs text-gray-500">
                                    <?php echo date("h:i A", strtotime($notification->CreatedDate)); ?>
                                </span>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                </div>
            <?php endif; ?>
            <?php
            if ($user->hasPermission("admin")):
            ?>
                <a class="logout-btn" href="./dashboard.php?src=index">Dashboard</a>
            <?php endif; ?>
            <a class="logout-btn" href="./logout.php">Logout</a>
        </div>
    </nav>

    <div>
    </div>
    <div class="container">

        <div class="sidebar">
            <?php
            if ($user->hasPermission("student")): // If the user has the "student" permission
            ?>
                <h2>Student Dashboard</h2>
                <a href="./dashboard.php?src=individualstudents&id="
                    style="<?php echo $current_page === 'individualstudents' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    View Profile
                </a>
                <a href="./dashboard.php?src=managequestion"
                    style="<?php echo $current_page === 'managequestion' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    View Question Bank
                </a>
                <a href="./dashboard.php?src=viewtimetable"
                    style="<?php echo $current_page === 'viewtimetable' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    View Timetable
                </a>
                <a href="./dashboard.php?src=viewattendance"
                    style="<?php echo $current_page === 'viewattendance' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    View Attendance
                </a>
            <?php
            elseif ($user->hasPermission("admin")): // If the user has the "admin" permission
            ?>
                <h2>College Management</h2>
                <a href="./dashboard.php?src=newcollegedetails"
                    style="<?php echo $current_page === 'newcollegedetails' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Add College Details
                </a>

                <a href="./dashboard.php?src=managecollegedetails"
                    style="<?php echo $current_page === 'managecollegedetails' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Manage College Details
                </a>

                <h2>Students Profile Management</h2>
                <a href="./dashboard.php?src=newstudents"
                    style="<?php echo $current_page === 'newstudents' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Add New Students
                </a>
                <a href="./dashboard.php?src=managestudents"
                    style="<?php echo $current_page === 'managestudents' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Manage Students
                </a>
                <a href="./dashboard.php?src=allstudentsprofiles"
                    style="<?php echo $current_page === 'allstudentsprofiles' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    All Students Details
                </a>

                <h2>Staff Profile Management</h2>
                <a href="./dashboard.php?src=newstaff"
                    style="<?php echo $current_page === 'newstaff' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Add New Staff
                </a>
                <a href="./dashboard.php?src=managestaff"
                    style="<?php echo $current_page === 'managestaff' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Manage Staff
                </a>

                <a href="./dashboard.php?src=assignstaff"
                    style="<?php echo $current_page === 'assignstaff' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Staff Assign
                </a>

                <h2>Question Bank Management</h2>
                <a href="./dashboard.php?src=addnewquestion"
                    style="<?php echo $current_page === 'addnewquestion' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Add New Questions
                </a>
                <a href="./dashboard.php?src=managequestion"
                    style="<?php echo $current_page === 'managequestion' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Manage Questions
                </a>

                <h2>Timetable Management</h2>
                <a href="./dashboard.php?src=uploadtimetable"
                    style="<?php echo $current_page === 'uploadtimetable' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Add New Timetable
                </a>
                <a href="./dashboard.php?src=Viewtimetable"
                    style="<?php echo $current_page === 'Viewtimetable' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Manage New Timetable
                </a>

                <h2>Internal Mark Management</h2>
                <a href="./dashboard.php?src=addinternalmark"
                    style="<?php echo $current_page === 'addinternalmark' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Add New Marks
                </a>
                <a href="./dashboard.php?src=viewinternalmark"
                    style="<?php echo $current_page === 'viewinternalmark' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Manage all marks
                </a>
                <a href="./dashboard.php?src=Overall"
                    style="<?php echo $current_page === 'Overall' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Overall
                </a>

                <a href="./dashboard.php?src=YearData"
                    style="<?php echo $current_page === 'YearData' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Year Data
                </a>

                <h2>University Mark Management</h2>
                <a href="./dashboard.php?src=adduniversitymark"
                    style="<?php echo $current_page === 'adduniversitymark' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Add New Marks
                </a>
                <a href="./dashboard.php?src=viewuniversitymark"
                    style="<?php echo $current_page === 'viewuniversitymark' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Manage all marks
                </a>
                <a href="./dashboard.php?src=OverallMark"
                    style="<?php echo $current_page === 'OverallMark' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    OverallMark
                </a>

                <h2>Attendance Management</h2>
                <a href="./dashboard.php?src=addattendance"
                    style="<?php echo $current_page === 'addattendance' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    New Update
                </a>
                <a href="./dashboard.php?src=viewattendance"
                    style="<?php echo $current_page === 'viewattendance' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Manage Update
                </a>

                <h2>Notification Management</h2>
                <a href="./dashboard.php?src=newnotification"
                    style="<?php echo $current_page === 'newnotification' ? 'background-color: #3498db; color: white; font-weight: bold;' : ''; ?>">
                    Add New Notification
                </a>
            <?php endif; ?>
        </div>

        <div class="main-content">
            <main>