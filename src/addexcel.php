<?php
// Get the PDO connection instance
$db = DB::getInstance()->getConnection();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Import Excel File To MySql Database">
    <title>Import Excel To MySQL Database</title>
</head>
<style>


#wrap {
    display: flex;
    justify-content: center; 
    align-items: center; 
    height: calc(70vh - 60px);  
    width: 100%;
}

.row {
    display: flex;
    justify-content: center; 
    width: 100%;
}

/* Form center styles */
#form-login {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;  
}

legend {
    font-size: 1.6rem;
    color: #333;
    margin-bottom: 20px;
}

.control-group {
    margin-bottom: 20px;
}

.control-label {
    font-weight: bold;
    margin-bottom: 10px;
}

.controls {
    margin-bottom: 10px;
}

input[type="file"] {
    padding: 8px;
    font-size: 1rem;
    border-radius: 4px;
    border: 1px solid #ddd;
    width: 100%;
}

button[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

button[type="submit"]:active {
    background-color: #004085;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .span3 {
        display: none;
    }

    .span6 {
        width: 100%;
    }
}

@media screen and (max-width: 576px) {
    body {
        font-size: 14px;
    }

    .navbar .navbar-inner .container .brand {
        font-size: 1.2rem;
    }

    #form-login {
        padding: 20px;
    }

    button[type="submit"] {
        width: 100%;
    }
}
</style>
<body>

    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="#">Import Excel To MySQL Database</a>
            </div>
        </div>
    </div>

    <div id="wrap">
        <div class="container">
            <div class="row">
                <div class="span3 hidden-phone"></div>
                <div class="span6" id="form-login">
                    <form class="form-horizontal well" action="dashboard.php?src=import" method="post" name="upload_excel" enctype="multipart/form-data">
                        <fieldset>
                            <legend>Import CSV/Excel file</legend>
                            <div class="control-group">
                                <div class="control-label">
                                    <label for="file">CSV/Excel File:</label>
                                </div>
                                <div class="controls">
                                    <input type="file" name="file" id="file" class="input-large">
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="span3 hidden-phone"></div>
            </div>

            <!-- Table displaying student details -->
            
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
