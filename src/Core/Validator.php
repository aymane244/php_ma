<?php

namespace Vendor\PhpMa\Core;

class Validator{

    public static function string($val){
        $value = trim($val);
        return strlen($value) > 0;
    }

    public static function minimum_strings($val, $min){
        $value = trim($val);
        return strlen($value) >= $min;
    }

    public static function maximum_strings($val, $max){
        $value = trim($val);
        return strlen($value) <= $max;
    }

    public static function exact_strings($val, $exact_number){
        $value = trim($val);
        return strlen($value) === $exact_number;
    }
    
    public static function confirm($password, $confirm){
        $password = trim($password);
        $confirm = trim($confirm);
        return $password === $confirm;
    }

    public static function text($value){
        return preg_match('/^[A-Za-z\s]+$/', $value) === 1;
    }

    public static function numeric($value){
        return is_numeric($value);
    }

    public static function check_password($value){
        $pattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]+$/';

        return preg_match($pattern, $value);
    }
    
    public static function confirm_array($values){
        $uniqueValues = array_unique($values);

        return count($values) === count($uniqueValues);
    }

    public static function check_email($value, $user_type){
        $table = $user_type !== "admin" ? $user_type . "s" : $user_type;
        $db = App::resolve(Database::class);
        $email = $db->select($table)->where([$user_type.'_email' => $value])->get();
        return count($email) === 0;
    }

    public static function matched_email($value, $user_type){
        $table = $user_type !== "admin" ? $user_type . "s" : $user_type;
        $db = App::resolve(Database::class);
        $email = $db->select($table)->where([$user_type.'_email' => $value])->get();
        return count($email) > 0;
    }

    public static function check_phone($value, $user_type){
        $table = $user_type !== "admin" ? $user_type . "s" : $user_type;
        $db = App::resolve(Database::class);
        $phone = "";

        if(strpos($value, "0") !== 0){
            $phone = "0".$value;
        }else{
            $phone = $value;
        }

        $phone = $db->select($table)->where([$user_type.'_phone' => $phone])->get();
        return count($phone) === 0;
    }

    public static function phone_number($value){
        $prefix = strpos($value, "06") === 0 || strpos($value, "07") === 0 || strpos($value, "6") === 0 || strpos($value, "7") === 0;
        return $prefix;
    }
}