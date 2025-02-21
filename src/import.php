<?php
require_once "./core/init.php";

// Get PDO database connection instance
$db = DB::getInstance()->getConnection();

if (isset($_POST["Import"])) {
    // Get the file uploaded
    $filename = $_FILES["file"]["tmp_name"];

    // Check if the file size is greater than 0 (i.e., file is selected)
    if ($_FILES["file"]["size"] > 0) {
        // Open the CSV file for reading
        $file = fopen($filename, "r");

        // Skip the first row (optional, if headers are present in the CSV file)
        fgetcsv($file);

        // Prepare the SQL query with placeholders for values
        $sql = "INSERT INTO students_details (
                    `Name`, `Email`, `Mobile_no`, 
                    `DoB`, `Community`, `Religion`, 
                    `Nationality`, `Register_number`, 
                    `Admission_number`, `Joining_year`, 
                    `Department`, `YoP`, `Number_of_years`, 
                    `Number_of_semesters`, `Mark10`, 
                    `Mark12`, `TC_issue`, `AbcId`, 
                    `Apaar`, `Batch`, `Branch`, 
                    `Father_Name`, `Mother_Name`, `Mother_Tongue`, 
                    `Marital_Status`, `Husband_Name`, `Blood_group`, 
                    `Residential_Address`, `Aadhar_Number`, `BankName`, 
                    `Ifsc`, `AccountNumber`
                ) VALUES (
                    :Name, :Email, :Mobile_no, :DoB, :Community, :Religion, 
                    :Nationality, :Register_number, :Admission_number, :Joining_year, 
                    :Department, :YoP, :Number_of_years, :Number_of_semesters, 
                    :Mark10, :Mark12, :TC_issue, :AbcId, :Apaar, :Batch, 
                    :Branch, :Father_Name, :Mother_Name, :Mother_Tongue, 
                    :Marital_Status, :Husband_Name, :Blood_group, :Residential_Address, 
                    :Aadhar_Number, :BankName, :Ifsc, :AccountNumber
                )";

        // Prepare the statement
        $stmt = $db->prepare($sql);

        // Iterate through each row in the CSV file
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
            // Bind parameters to the prepared statement
            $stmt->bindParam(':Name', $emapData[0]);
            $stmt->bindParam(':Email', $emapData[1]);
            $stmt->bindParam(':Mobile_no', $emapData[2]);
            $stmt->bindParam(':DoB', $emapData[3]);
            $stmt->bindParam(':Community', $emapData[4]);
            $stmt->bindParam(':Religion', $emapData[5]);
            $stmt->bindParam(':Nationality', $emapData[6]);
            $stmt->bindParam(':Register_number', $emapData[7]);
            $stmt->bindParam(':Admission_number', $emapData[8]);
            $stmt->bindParam(':Joining_year', $emapData[9]);
            $stmt->bindParam(':Department', $emapData[10]);
            $stmt->bindParam(':YoP', $emapData[11]);
            $stmt->bindParam(':Number_of_years', $emapData[12]);
            $stmt->bindParam(':Number_of_semesters', $emapData[13]);
            $stmt->bindParam(':Mark10', $emapData[14]);
            $stmt->bindParam(':Mark12', $emapData[15]);
            $stmt->bindParam(':TC_issue', $emapData[16]);
            $stmt->bindParam(':AbcId', $emapData[17]);
            $stmt->bindParam(':Apaar', $emapData[18]);
            $stmt->bindParam(':Batch', $emapData[19]);
            $stmt->bindParam(':Branch', $emapData[20]);
            $stmt->bindParam(':Father_Name', $emapData[21]);
            $stmt->bindParam(':Mother_Name', $emapData[22]);
            $stmt->bindParam(':Mother_Tongue', $emapData[23]);
            $stmt->bindParam(':Marital_Status', $emapData[24]);
            $stmt->bindParam(':Husband_Name', $emapData[25]);
            $stmt->bindParam(':Blood_group', $emapData[26]);
            $stmt->bindParam(':Residential_Address', $emapData[27]);
            $stmt->bindParam(':Aadhar_Number', $emapData[28]);
            $stmt->bindParam(':BankName', $emapData[29]);
            $stmt->bindParam(':Ifsc', $emapData[30]);
            $stmt->bindParam(':AccountNumber', $emapData[31]);

            // Execute the prepared statement
            $stmt->execute();
        }

        // Close the file after processing
        fclose($file);

        // Display a success message
        echo "<script type=\"text/javascript\">
            alert(\"CSV File has been successfully Imported.\");
            window.location = \"dashboard.php?src=addexcel\"
        </script>";
    } else {
        echo "<script type=\"text/javascript\">
            alert(\"No file selected or file size is 0.\");
            window.location = \"dashboard.php?src=addexcel\"
        </script>";
    }
}
?>
