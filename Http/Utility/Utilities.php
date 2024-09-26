<?php
namespace Http\Utility;

use Core\App;
use Core\Database;
use Core\Session;

class Utilities{
    protected $db;
    protected $error;
    protected $success;

    public function __construct(){
        $db = App::resolve(Database::class);
        $error = Session::get('errors');
        $success = Session::get('success');

        $this->db = $db;
        $this->error = $error;
        $this->success = $success;
    }
}