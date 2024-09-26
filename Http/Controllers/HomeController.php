<?php

namespace Http\Controllers;

use Database\Seeders;
use Http\Utility\Utilities;

class HomeController extends Utilities{

    public function index(){
        $name = 'Home';
        Seeders::insertTable();

        return view("index.view.php", [
            "name" => $name,
        ]);
    }
}