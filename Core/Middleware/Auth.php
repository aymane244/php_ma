<?php

namespace Core\Middleware;

use Core\App;
use Core\Database;

class Auth{

    protected $db;

    public function __construct(){
        $this->db = App::resolve(Database::class);
    }

    public function handle($user_type){
        if(!$_SESSION[$user_type] ?? false){
            header('location: /'.$user_type.'/login');
            die(); 
        }
    }

    public static function id($user_type){
        return isset($_SESSION[$user_type]['id']) ? $_SESSION[$user_type]['id'] : null;
    }
}