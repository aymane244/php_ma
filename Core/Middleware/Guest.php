<?php

namespace Core\Middleware;

class Guest{

    public function handle($user_type){
        if($_SESSION[$user_type] ?? false){
            header('location: /'.$user_type.'/login');
            die();
        }
    }
}