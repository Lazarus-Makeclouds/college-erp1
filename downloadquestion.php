<?php
require_once "./core/init.php"; 


if (Input::exists('get')) {
    $db = DB::getInstance();
    $id = Input::get('id'); 

    
    $questions = $db->get('questions', ['id', '=', $id])->first();

    
    if ($questions) {
       
        $pdfData = $questions->PDF;  
        
        
        if (empty($pdfData)) {
            echo "Error: PDF data is missing or corrupted.";
            exit();
        }

       
        header("Content-Type: application/pdf"); 
        header("Content-Disposition: attachment; filename={$questions->filename}"); 
        header("Content-Length: " . strlen($pdfData)); 

        
        ob_clean();  
        flush();    

       
        echo $pdfData; 

        exit(); 
    } else {
        
        echo "File not found!";
    }

    
    Redirect::to('./dashboard.php?src=managequestion');
} else {
    
    Redirect::to('../index.php');
}
?>
