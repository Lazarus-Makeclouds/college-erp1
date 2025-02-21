<?php
require_once "./core/init.php";
$db = DB::getInstance();
if (Input::exists('post')) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'Name' => array(
            'required' => true
        ),
        'Nationality' => array(
            'required' => true
        ),
        'Marital_Status' => array(
            'required' => true
        ),
        'Husband_Name' => array(
            'required' => true
        ),
        'DoB' => array(
            'required' => true
        ),
        'Joining_year'=>array(
            'required' => true
        ),
        'Email' => array(
            'required' => true,
            'unique' => 'students_details'
        ),
        'Register_number' => array(
            'required' => true
        ),
        'Admission_number' => array(
            'required' => true
        ),
        'YoP' => array(
            'required' => true
        ),
        'Number_of_semesters' => array(
            'required' => true
        ),
        'Number_of_years' => array(
            'required' => true
        ),
        'Mother_Name' => array(
            'required' => true
        ),
        'Father_Name' => array(
            'required' => true
        ),
        'Mobile_no' => array(
            'required' => true
        ),
        'Mother_Tongue' => array(
            'required' => true
        ),
        'Blood_group' => array(
            'required' => true
        ),
        'Aadhar_number' => array(
            'required' => true
        ),
        'AbcId' => array(
            'required' => true
        ),
        'Apaar' => array(
            'required' => true
        ),
        'Department' => array(
            'required' => true
        ),
        'Batch' => array(
            'required' => true
        ),
        'Branch' => array(
            'required' => true
        ),
        'Community' => array(
            'required' => true
        ),
        'Religion' => array(
            'required' => true
        ),
        'BankName' => array(
            'required' => true
        ),
        'Ifsc' => array(
            'required' => true
        ),
        'AccountNumber' => array(
            'required' => true
        ),
        'TC_issue' => array(
            'required' => true
        )
        
    ));
    if ($validation->passed()) {
        try {
            $data = array(
        "filetype" => Input::get('Image', 'type'),
        "Image" => Input::image_resize(400, 400, Input::get('Image', 'tmp_name'), Input::get('Image', 'type'), Input::get('Image', 'size')),
        "Name" => Input::get('Name'),
        "Email" => Input::get('Email'),
        "Register_number" => Input::get('Register_number'),
        "Admission_number" => Input::get('Admission_number'),
        "DoB" => Input::get('DoB'),
        "YoP" =>Input::get('YoP'),
        "Joining_year" =>Input::get('Joining_year'),
        "Father_Name" => Input::get('Father_Name'),
        "Mother_Name" => Input::get('Mother_Name'),
        "Mother_Tongue" => Input::get('Mother_Tongue'),
        "Marital_Status" => Input::get('Marital_Status'),
        "Husband_Name" => Input::get('Husband_Name'),
        "Blood_group" => Input::get('Blood_group'),
        "Residential_Address" => Input::get("Residential_Address"),
        "Aadhar_number" => Input::get('Aadhar_number'),
        "AbcId" => Input::get('AbcId'),
        "Apaar" => Input::get("Apaar"),
        "Department" => Input::get("Department"),
        "Batch" => Input::get('Batch'),
        "Branch" => Input::get('Branch'),
        "Mark10" => Input::get('Marks10'),
        "Mark12" => Input::get( 'Marks12'),
        "Community" => Input::get('Community'),
        "Religion" => Input::get('Religion'),
        "Nationality" => Input::get('Nationality'),
        "BankName" => Input::get('BankName'),
        "Ifsc" => Input::get('Ifsc'),
        "AccountNumber" => Input::get('AccountNumber'),
        "Mobile_no" => Input::get('Mobile_no'),
        "TC_issue" => Input::get('TC_issue'),
        "Number_of_years" => Input::get('Number_of_years'),
        "Number_of_semesters" => Input::get('Number_of_semesters')
    );
    if($db->insert("students_details", $data)){
        if ($db->insert("users",array(
            "Email"=>Input::get('Email'),
            "Password"=>Hash::make(Input::get('Mobile_no')),
            "group"=>3
        ))){
            echo "New Student added successfully"; 
        }  
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
} else {
foreach ($validation->errors() as $error) {
    echo str_replace("_", " ", $error);
}
}
}