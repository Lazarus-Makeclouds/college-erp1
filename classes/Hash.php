<?php
class Hash {
    public static function make($string){
        $option = [
            'cost'=> 12
        ];
        $hashed = password_hash($string, PASSWORD_BCRYPT, $option);
        return $hashed;
    }

    public static function check($pwd, $password){
        if (password_verify($password,$pwd)){
            return true;
        } else {
            return false;
        }
    }

    public static function make_hash($string, $salt=''){
        return hash('sha256', $string.$salt);
    }

    public static function unique () {
        return self::make_hash(uniqid());        
    }

    public static function hash_table($string){
        return hash('adler32', $string);
    }
}