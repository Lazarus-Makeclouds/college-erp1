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
        'Email' => array(
            'required' => true,
            'unique' => 'teachers_details'
        ),
        'Level' => array(
            'required' => true
        ),
        'Major' => array(
            'required' => true
        ),
        'FOS' => array(
            'required' => true
        ),
        'InstitudeUniversity' => array(
            'required' => true
        ),
        'Locatedin' => array(
            'required' => true
        ),
        'Graduation' => array(
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
        'InstitutionName' => array(
            'required' => true
        ),
        'PositionTitle' => array(
            'required' => true
        ),
        'PositionLevel' => array(
            'required' => true
        ),
        'Specialization' => array(
            'required' => true
        ),
        'Industry' => array(
            'required' => true
        ),
        'DoJ' => array(
            'required' => true
        ),
        'Skill' => array(
            'required' => true
        ),
        'Language' => array(
            'required' => true
        )
    ));
    if ($validation->passed()) {
        try {
            $data = array(
                "Image" => Input::image_resize(400, 400, Input::get('Image', 'tmp_name'), Input::get('Image', 'type'), Input::get('Image', 'size')),
                "filetype" => Input::get('Image', 'type'),
                "Name" => Input::get('Name'),
                "Nationality" => Input::get('Nationality'),
                "Marital_Status" => Input::get('Marital_Status'),
                "Husband_Name" => Input::get('Husband_Name'),
                "DoB" => Input::get('DoB'),
                "Email" => Input::get('Email'),
                "Residential_Address" => Input::get('Residential_Address'),
                "Level" => Input::get('Level'),
                "Major" => Input::get('Major'),
                "FOS" => Input::get('FOS'),
                "InstitudeUniversity" => Input::get('InstitudeUniversity'),
                "Locatedin" => Input::get('Locatedin'),
                "Graduation" => Input::get('Graduation'),
                "Father_Name" => Input::get('Father_Name'),
                "Mother_Name" => Input::get('Mother_Name'),
                "Mobile_no" => Input::get('Mobile_no'),
                "InstitutionName" => Input::get('InstitutionName'),
                "PositionTitle" => Input::get('PositionTitle'),
                "PositionLevel" => Input::get('PositionLevel'),
                "Specialization" => Input::get('Specialization'),
                "Industry" => Input::get('Industry'),
                "DoJ" => Input::get('DoJ'),
                "Skill" => Input::get('Skill'),
                "Language" => Input::get('Language')
            );
            if ($db->insert('teachers_details', $data)) {
                if ($db->insert('users', array(
                    "Email" => Input::get('Email'),
                    "Password" => Hash::make(Input::get('Mobile_no')),
                    "group" => 2 )))
                {
                    echo "New Staff added successfully";
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
