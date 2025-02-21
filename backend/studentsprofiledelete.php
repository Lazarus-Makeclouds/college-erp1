<?php 
require_once "./core/init.php";
if (Input::exists('get')){
    $db = DB::getInstance();
    $id = Input::get('id');
    $email = $db->get("students_details",["id", "=" ,$id])->first()->Email;
    if($db->delete('students_details', ['id','=',$id])){
        if($db->delete("users",["Email","=",$email])){
            Redirect::to('../dashboard.php?src=managestudents');
        }
    } else {
        Session::flash('delete_error','Error deleting student');
        Redirect::to('../dashboard.php?src=managestudents');
    }
}else{
    Redirect::to( '../dashboard.php?src=managestudents');
}