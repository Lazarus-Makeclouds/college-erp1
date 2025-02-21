<?php
require_once "./core/init.php";
$db = DB::getInstance();
if (Input::exists('post')) {
    $NotificationID = Input::get('Notification-id');
        try {
            // Update the database using the reference method
            if ($db->update('notifications', $NotificationID, 'NotificationID', ["Notification"=>Input::get("notification-text")])) {
                echo "Notification updated successfully";
            } else {
                echo "Failed to update notification";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        foreach ($validation->errors() as $error) {
            echo str_replace("_", " ", $error);
        }
    }

