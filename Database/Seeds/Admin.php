<?php

namespace Database\Seeds;

class Admin{

    public static function getAdminData(){
        $password = password_hash("3iyadat/2024??", PASSWORD_BCRYPT);
        
        return [
            "admin_first_name" => "aimane",
            "admin_last_name" => "chnaif",
            "admin_email" => "a.chnaif2010@gmail.com",
            "admin_password" => $password,
        ];
    }
}