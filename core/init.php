<?php
session_start();

require_once 'global.php';

spl_autoload_register(function($class){
    require_once './classes/'.$class.'.php';
});

require_once 'functions/sanitize.php';


// // functionality to stay logged in
// if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
//     $hash = Cookie::get(Config::get('remember/cookie_name'));
//     $hashCheck = DB::getInstance()->get('user_session', array('hash', '=', $hash));

//     if($hashCheck->count()){
//         $user= new User($hashCheck->first()->user_id);
//         $user->login();
//     }
// }