<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College ERP Software - Make Academy</title>


    <meta name="description" content="Simplify college operations with our advanced College ERP software. Manage students, courses, fees, attendance, and more.">
    <meta name="keywords" content="college ERP, student management, course scheduling, fee management, attendance tracking, education ERP, campus solutions">
    <meta name="geo.placename" content="Krishnagiri, Tamil Nadu, India">
    <meta name="geo.region" content="IN-TN">


    <link rel="icon" href="./assets/favicon.png" type="image/png">


    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" />


    <style>
        .header {
            background: #29aae2ad;
            color: white;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button {
            background: #29aae2ad;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: background 0.3s ease;
        }

        .cta-button:hover {
            background: #29aae3ad;
        }
    </style>
</head>

<body class="bg-gray-50 font-poppins relative">



    <header class="header py-4">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a class="dark-logo" href="https://svvcaskgi.com/">
                <img src="./assets/img1.jpg" class="w-40">
                <nav class="flex items-center space-x-6 text-white">

                    <a href="./logout.php" class="hover:underline">Logout</a>

                    <a href="./notifications.php" class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="currentColor" class="icon">
                            <path d="M32.51 27.83A14.4 14.4 0 0 1 30 24.9a12.6 12.6 0 0 1-1.35-4.81v-4.94A10.81 10.81 0 0 0 19.21 4.4V3.11a1.33 1.33 0 1 0-2.67 0v1.31a10.81 10.81 0 0 0-9.33 10.73v4.94a12.6 12.6 0 0 1-1.35 4.81a14.4 14.4 0 0 1-2.47 2.93a1 1 0 0 0-.34.75v1.36a1 1 0 0 0 1 1h27.8a1 1 0 0 0 1-1v-1.36a1 1 0 0 0-.34-.75M5.13 28.94a16.2 16.2 0 0 0 2.44-3a14.2 14.2 0 0 0 1.65-5.85v-4.94a8.74 8.74 0 1 1 17.47 0v4.94a14.2 14.2 0 0 0 1.65 5.85a16.2 16.2 0 0 0 2.44 3Z" />
                            <path d="M18 34.28A2.67 2.67 0 0 0 20.58 32h-5.26A2.67 2.67 0 0 0 18 34.28" />
                            <path fill="none" d="M0 0h36v36H0z" />
                        </svg>

                    </a>
                </nav>

        </div>
    </header>

    <main class="container mx-auto py-10 px-6">
        <centre><a class="dark-logo" href="https://svvcaskgi.com/">
                <img src="./assets/img1.jpg" alt="SVVCASKGI Logo">
            </a>


            <main class="container mx-auto py-12 px-6">
                <section id="features">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="feature-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 text-center">
                            <i class="fas fa-users text-5xl text-blue-500 mb-4"></i>
                            <h3 class="text-2xl font-semibold mb-2">Your Profile</h3>
                            <p class="text-gray-600">Manage your entire profile.</p>
                            <a href="" class="text-blue-500 underline">Manage your profile</a>
                        </div>

                        <div class="feature-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-6 text-center">
                            <i class="fa-solid fa-book text-5xl text-gray-700 mb-4"></i>
                            <h3 class="text-2xl font-semibold mb-2">Question Bank</h3>
                            <p class="text-gray-600">Access previous year question papers.</p>
                            <a href="src/studentquestionbank.php" class="text-gray-700 underline">View Question Bank</a>  
                        </div>

                        <div class="feature-card bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 text-center">
                            <i class="fa-solid fa-business-time text-5xl text-yellow-500 mb-4"></i>
                            <h3 class="text-2xl font-semibold mb-2">TimeTable Management</h3>
                            <p class="text-gray-600">Organize classes and schedules.</p>
                            <a href="" class="text-yellow-500 underline">Manage Timetables</a>
                        </div>

                        <div class="feature-card bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 text-center">
                            <i class="fas fa-chart-line text-5xl text-green-500 mb-4"></i>
                            <h3 class="text-2xl font-semibold mb-2">Internal Result Analysis</h3>
                            <p class="text-gray-600">Analyze student performance easily.</p>
                            <a href="" class="text-green-500 underline">Analyze Results</a>
                        </div>

                        <div class="feature-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 text-center">
                            <i class="fas fa-clipboard-list text-5xl text-purple-500 mb-4"></i>
                            <h3 class="text-2xl font-semibold mb-2">Attendance Tracking</h3>
                            <p class="text-gray-600">Track attendance efficiently.</p>
                            <a href="src/studentattendancetracker.php" class="text-purple-500 underline"> Track Attendance</a>
                        </div>
                    </div>
                </section>
            </main>
</body>

</html>