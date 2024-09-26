<?php

use Vendor\PhpMa\Core\Router;
use Vendor\PhpMa\Core\Session;
use Vendor\PhpMa\Core\ValidationException;

$params = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => 604800,
    'path' => $params['path'],
    'domain' => $params['domain'],
    'secure' => $params['secure'],
    'httponly' => $params['httponly'],
    'samesite' => $params['samesite'] ?? 'Lax'
]);

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

const BASE_PATH = __DIR__ . '/../';
const BASE_URL = 'http://localhost:8000';

require BASE_PATH . "/vendor/autoload.php";

require BASE_PATH . "Core/functions.php";
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

require base_path("bootstrap.php");

$router = new Router();

$routes = require base_path("routes/web.php");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if(in_array($method, ['POST', 'PUT', 'DELETE'])){
    $csrfToken = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    
    if(!verify_csrf_token($csrfToken)){
        throw new ValidationException('Invalid CSRF token');
    }
}

try{
    $router->route($uri, $method);
}catch(ValidationException $exception){
    Session::flash('errors', $exception->errors);
    Session::flash('old', $exception->old);

    return redirect($router->previous_url());
}
Session::unflash();