<?php

namespace Core\Middleware;

class Middleware{

    public const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
    ];

    public static function resolve($key, $user_type){
        if(!$key){
            return;
        }
        
        $middleware = static::MAP[$key] ?? null;

        if(!$middleware){
            throw new \Exception("No matching middleware for key ". $key);
        }
        
        $middlewareInstance = new $middleware();
        $middlewareInstance->handle($user_type);
    }
}