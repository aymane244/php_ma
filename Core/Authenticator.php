<?php

namespace Core;

class Authenticator{

    protected $db;

    public function __construct(){
        $this->db = App::resolve(Database::class);
    }

    public function attempt($email, $password, $user_type){
        $table = $this->getTable($user_type);
        $user = $this->db->select($table)->where([$user_type.'_email' => $email])->first();
        
        if($user){
            if(password_verify($password, $user->{$user_type . '_password'})){
                return $this->login($user->{$user_type . '_first_name'}, 
                $user->{$user_type . '_last_name'}, 
                $user->{$user_type . '_id'}, $user_type);            
            }
        }

        return false;
    }

    public function verify($email, $user_type){
        $table = $this->getTable($user_type);
        $user = $this->db->select($table)->where([$user_type.'_email' => $email])->first();
        
        if($user && $user->{$user_type . '_email_verified_at'}){
            return true;
        }

        return false;
    }

    public function login($first_name, $last_name, $id, $user_type){
        $_SESSION[$user_type] = [
            'first_name' => $first_name, 
            'last_name' => $last_name,
            'id' => $id, 
        ];
        
        session_regenerate_id(true);
        return $_SESSION[$user_type];
    }

    public static function logout($user_type){
        unset($_SESSION[$user_type]);
        session_regenerate_id(true);
    }

    protected function getTable($user_type){
        return $user_type !== "admin" ? $user_type . "s" : $user_type;
    }
}