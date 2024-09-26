<?php

use Core\Session;

$lang = Session::language('lang');

require base_path("lang/".$lang.".php");