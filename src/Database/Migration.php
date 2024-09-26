<?php

namespace Vendor\PhpMa\Database;

use Vendor\PhpMa\Database\Seeds\Admin;
use Vendor\PhpMa\Http\Utility\Utilities;

class Migration extends Utilities{

    protected $table;

    public function defineTable($table){
        $this->table = $table;
    }

    public function insertAdmin(){
        $this->table = "admin";
        $this->db->truncate($this->table);
        $adminData = Admin::getAdminData();
       
        $this->db->insert($this->table, $adminData);
    }
}