<?php

use Vendor\PhpMa\Core\Session;

$lang = Session::language('lang');

require base_path("lang/".$lang.".php");