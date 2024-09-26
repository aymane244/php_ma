<?php

namespace Core;

use Core\Middleware\Middleware;

class Router {

    protected $routes = [];

    // Add a new route to the router
    public function add($method, $uri, $controller, $user_type){
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => strtoupper($method),
            'middleware' => null,
            'user_type' => $user_type,
        ];
        return $this;
    }

    // Add middleware to the most recently added route
    public function only($key){
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;
        return $this;
    }

    // HTTP method helpers
    public function get($uri, $controller, $user_type = ""){
        return $this->add('GET', $uri, $controller, $user_type);
    }

    public function post($uri, $controller, $user_type = ""){
        return $this->add('POST', $uri, $controller, $user_type);
    }

    public function delete($uri, $controller, $user_type = ""){
        return $this->add('DELETE', $uri, $controller, $user_type);
    }

    public function put($uri, $controller, $user_type = ""){
        return $this->add('PUT', $uri, $controller, $user_type);
    }

    public function patch($uri, $controller, $user_type = ""){
        return $this->add('PATCH', $uri, $controller, $user_type);
    }

    // Find and resolve the route
    public function route($uri, $method){
        foreach ($this->routes as $route) {
            if ($this->matchUri($route['uri'], $uri) && $route['method'] === strtoupper($method)) {
                if ($route['middleware']) {
                    Middleware::resolve($route['middleware'], $route['user_type']);
                }
    
                if (!$this->userHasAccess($route['user_type'])) {
                    // Abort with 403 Forbidden if user doesn't have access
                    $this->abort(Response::FORBIDDEN);
                }
    
                return $this->resolveController($route['controller']);
            }
        }
    
        // If no route matches, abort with 404 Not Found
        $this->abort(Response::NOT_FOUND);
    }
    

    // Match the URI including support for parameters
    protected function matchUri($routeUri, $requestedUri){
        $pattern = preg_replace('/\{[a-zA-Z]+\}/', '([a-zA-Z0-9]+)', $routeUri);
        return preg_match("#^$pattern$#", $requestedUri);
    }

    // Resolve the controller
    protected function resolveController($controller){
        try{
            if(is_array($controller)){
                [$class, $method] = $controller;
                $instance = new $class();
                return $instance->$method();
            }else{
                return require base_path("Http/controllers/" . $controller);
            }
        }catch(\Exception $e){
            // If there is any exception, abort with 500 Internal Server Error
            $this->abort(Response::INTERNAL_SERVER_ERROR);
        }
    }

    protected function userHasAccess($user_type){
        $currentUserType = $_SESSION[$user_type] ?? 'guest';

        return $currentUserType === $user_type;
    }

    // Get the previous URL
    public function previous_url(){
        return $_SERVER['HTTP_REFERER'] ?? '/';
    }

    // Abort with a status code
    protected function abort($status = Response::NOT_FOUND){
        http_response_code($status);

        // Load appropriate error view based on the status code
        if(file_exists(base_path("views/{$status}.php"))){
            require base_path("views/{$status}.php");
        }else{
            // Fallback message if no view exists for this status code
            echo "Error: {$status}";
        }

        die();
    }
}