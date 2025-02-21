<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College ERP</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" />
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<style>
    .header {
        background: #29aae2ad;
        color: white;
    }


    .cta-button {
        background: #29aae2;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        transition: background 0.3s ease;
    }

    .cta-button:hover {
        background: #1e90ff;
    }
</style>

<body>

    <!-- Navigation -->
    <header class="header py-4">
        <div class="container mx-auto flex justify-between items-center px-6">
            <img src="./assets/img1.jpg" alt="Make Labs Logo" class="w-32">
            <nav class="space-x-6 text-white">
            <a href="./logout.php" class="hover:underline">Logout</a>
            </nav>
        </div>
    </header>