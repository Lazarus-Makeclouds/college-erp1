<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && Input::get('src') === 'deleteallocation') {
    $id = Input::get('id');

    if (empty($id)) {
        Session::flash('delete_error', 'No ID specified.');
        Redirect::to('./dashboard.php?src=managesubjectallocation');
        exit();
    }

    // Delete the record from the database
    $delete = $db->delete('subject_allocation', ['id', '=', $id]);

    if ($delete) {
        Session::flash('delete_success', 'Subject allocation deleted successfully.');
    } else {
        Session::flash('delete_error', 'Failed to delete subject allocation.');
    }

    // Redirect back to the main page
    Redirect::to('./dashboard.php?src=managesubjectallocation');
    exit();
}
?>
