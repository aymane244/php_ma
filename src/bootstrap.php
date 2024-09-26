<?php

use Vendor\PhpMa\Core\App;
use Vendor\PhpMa\Core\Container;
use Vendor\PhpMa\Core\Database;

$container = new Container();

$container->bind('Vendor\PhpMa\Core\Database', function(){
    $config = require base_path("config.php");
    return new Database($config['database']);
});

App::setContainer($container);