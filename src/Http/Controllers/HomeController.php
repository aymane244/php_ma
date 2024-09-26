<?php

namespace Vendor\PhpMa\Http\Controllers;

use Vendor\PhpMa\Database\Seeders;
use Vendor\PhpMa\Http\Utility\Utilities;

class HomeController extends Utilities{

    public function index(){
        $name = 'Home';
        Seeders::insertTable();

        return view("index.view.php", [
            "name" => $name,
        ]);
    }
}