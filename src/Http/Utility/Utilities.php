<?php
namespace Vendor\PhpMa\Http\Utility;

use Vendor\PhpMa\Core\App;
use Vendor\PhpMa\Core\Database;
use Vendor\PhpMa\Core\Session;

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