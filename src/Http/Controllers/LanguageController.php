<?php

namespace Vendor\PhpMa\Http\Controllers;

use Vendor\PhpMa\Core\Router;

class LanguageController{

    public function change(){
        $router = new Router();
        
        require base_path("lang/language.php");
        redirect($router->previous_url());
        exit();
    }
}