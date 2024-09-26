<?php

use Http\Controllers\HomeController;
use Http\Controllers\LanguageController;

// home page
$router->get('/', [HomeController::class, 'index']);
$router->get('/contact', [HomeController::class, 'contact']);

//language
$router->post('/lang', [LanguageController::class, 'change']);