<?php
session_start();

require_once "./../core/global.php";

spl_autoload_register(function($class){
    require_once '../classes/'.$class.'.php';
});

require_once '../functions/sanitize.php';