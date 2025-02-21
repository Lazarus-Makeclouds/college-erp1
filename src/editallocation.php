<?php

$db = DB::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && Input::get('src') === 'editallocation') {
    // Get the form data
    $id = Input::get('id');
    $name = Input::get('Name');
    $year = Input::get('Year');
    $department = Input::get('Department');
    $sem = Input::get('Sem');
    $subject = Input::get('subject');

    // Validate the input
    if (empty($id) || empty($name) || empty($year) || empty($department) || empty($sem) || empty($subject)) {
        Session::flash('edit_error', 'All fields are required.');
        Redirect::to('./dashboard.php?src=managesubjectallocation');
        exit();
    }

    // Update query with updated_at field
    $updateQuery = "UPDATE subject_allocation SET Name = ?, Year = ?, Department = ?, Sem = ?, subject = ?, updated_at = NOW() WHERE id = ?";

    // Execute the query with the provided data
    $result = $db->query($updateQuery, [$name, $year, $department, $sem, $subject, $id]);
    
    // Check if the update was successful
    if ($result) {
        Session::flash('edit_success', 'Subject allocation updated successfully.');
    } else {
        Session::flash('edit_error', 'Failed to update subject allocation.');
    }

    // Redirect back to the main page
    Redirect::to('./dashboard.php?src=managesubjectallocation');
    exit();
} else {
    // If the request method is not POST, redirect to the main page
    Redirect::to('./dashboard.php?src=managesubjectallocation');
    exit();
}
?>