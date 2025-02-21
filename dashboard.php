<?php
header("Access-Control-Allow-Origin: *");

// Allow specific HTTP methods (GET, POST, PUT, DELETE, etc.)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers (if needed, e.g., Authorization header)
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept, Access-Control-Request-Method, Access-Control-Request-Headers");

// Allow credentials if needed
header("Access-Control-Allow-Credentials: true");
require_once "./core/init.php";
$user = new User();
if (!$user->isLoggedIn()):
    Redirect::to('./index.php');
else:
    if (isset($_GET['src'])) :
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>ERP</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">



            <link rel="stylesheet" href="./css/<?= Input::get('src') ?>.css">
            <link rel="stylesheet" href="./css/tables.css" >

            <!-- Font Awesome -->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" />
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous">

            <!-- Include jsPDF and AutoTable -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

            <!-- Include SheetJS for Excel -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        </head>


<?php
        $page = "src/" . Input::get('src') . ".php";
        if (file_exists($page)) {
            require_once "includes/aside.php";
            require_once $page;
        } else {
            require_once "404.html";
        }
    // }elseif(isset($_GET['staff'])) {
    //     $page = "admin/" . Input::get('staff') . ".php";
    //     if (file_exists($page)) {
    //         require_once "includes/adminaside.php";
    //         require_once $page;
    //     } else {
    //         require_once "404.html";
    //     }

    endif;
endif;
?>





