<?php

namespace Vendor\PhpMa\Core;

class Session{

    public static function start(){
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
    }

    public static function has($key){
        return (bool) static::get($key);
    }

    public static function put($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null){
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function flash($key, $value){
        $_SESSION['_flash'][$key] = $value;
    }

    public static function unflash(){
        unset($_SESSION['_flash']);
    }

    public static function flush(){
        $_SESSION = [];
    }

    public static function forget($key){
        unset($_SESSION['_flash'][$key]);
    }

    public static function destroy(){
        static::flush();

        session_unset();
        session_destroy();
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    public static function language($langugage){
        if(isset($_POST[$langugage])){
            $_SESSION['_lang'] = $_POST[$langugage];
        }
        
        return $_SESSION['_lang'] ?? 'ar';
    }
}