<?php

namespace Vendor\PhpMa\Core\Middleware;

use Vendor\PhpMa\Core\App;
use Vendor\PhpMa\Core\Database;

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