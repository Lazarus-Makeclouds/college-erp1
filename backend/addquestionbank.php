<?php
require_once "./core/init.php";
$db = DB::getInstance();
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = new DateTime();
$time = $currentDateTime->format('Y-m-d');
$timestamp = $currentDateTime->format('Y-m-d_H-i-s'); // Format for including date and time

if (Input::exists()) {
    // Handle the file upload
    if (isset($_FILES['PDF']) && $_FILES['PDF']['error'] === UPLOAD_ERR_OK) {
        // Get the original filename and file content
        $originalFilename = $_FILES['PDF']['name'];
        $fileTmpName = $_FILES['PDF']['tmp_name'];
        
        // Generate a unique filename with timestamp
        $filenameWithTimestamp = $timestamp . '_' . $originalFilename;
        
        // Read the file content
        $fileContent = file_get_contents($fileTmpName);

        $data = array(
            "year" => Input::get('year'),
            "department" => Input::get('department'),
            "subject" => Input::get('subject'),
            "subject_code" => Input::get('subject_code'),
            "semester" => Input::get('sem'),
            "filename" => $filenameWithTimestamp, 
            "PDF" => $fileContent, 
            "t_date" => $time
        );

        // Insert the data into the database
        if ($db->insert('questions', $data)) {
            echo "Question paper saved successfully!";
        } else {
            echo "Error saving question paper to the database!";
        }
    } else {
        echo "No PDF file uploaded or there was an error with the upload!";
    }
}
?>
