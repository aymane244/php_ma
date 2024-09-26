<?php

use Vendor\PhpMa\Http\Controllers\HomeController;
use Vendor\PhpMa\Http\Controllers\LanguageController;

// home page
$router->get('/', [HomeController::class, 'index']);
$router->get('/contact', [HomeController::class, 'contact']);

//language
$router->post('/lang', [LanguageController::class, 'change']);