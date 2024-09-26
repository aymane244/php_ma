<?php

namespace Vendor\PhpMa\Database;

class Seeders{

    public static function insertTable(){
        $migration = new Migration();
        $migration->insertAdmin();
        
        return $migration;
    }
}