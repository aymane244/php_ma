<?php

namespace Database;

class Seeders{

    public static function insertTable(){
        $migration = new Migration();
        $migration->insertAdmin();
        
        return $migration;
    }
}