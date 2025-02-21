<?php
require_once __DIR__."/../vendor/autoload.php";
use Dotenv\Dotenv as Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$GLOBALS['config']=array(
    'mysql' => array(
        'host' => $_ENV['DB_HOST'],  //"localhost"
        'username' => $_ENV['DB_USERNAME'], //'root',
        'password' => $_ENV['DB_PASSWORD'], //'makelabs',
        'db' => $_ENV['DB_NAME'] //'lms'
    ),
    'remember' => array(
        'cookie_name' => $_ENV['REMEMBER_COOKIE_NAME'], //"hash",
        'cookie_expiry' => $_ENV['REMEMBER_COOKIE_EXPIRY'] // "604800",
    ),
    'session' => array(
        'session_name' => $_ENV['SESSION_NAME'], // "user", 
        'token_name' => $_ENV['TOKEN_NAME'] // "token" 
    )
);
