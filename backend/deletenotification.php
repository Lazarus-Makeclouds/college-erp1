<?php
require_once "./core/init.php";

$db = DB::getInstance(); 

if (Input::exists('get')) { 
    $id = Input::get('id');
    if($db->delete('notifications', ['NotificationID','=',$id])){
        Redirect::to('../dashboard.php?src=newnotification');
    } else {
        Redirect::to('../dashboard.php?src=newnotification');
    }
}else{
    Redirect::to('../dashboard.php?src=newnotification');
}