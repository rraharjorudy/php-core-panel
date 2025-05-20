<?php

namespace App\Core;

class Router
{
    
    protected array $routes = [];
    protected string $prefix = '';
    protected array $middlewares = [];

    public function get(string $uri, array $action, array $middleware = [])
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, array $action, array $middleware = [])
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function put(string $uri, array $action, array $middleware = [])
    {
        $this->addRoute('PUT', $uri, $action, $middleware);
    }

    public function delete(string $uri, array $action, array $middleware = [])
    {
        $this->addRoute('DELETE', $uri, $action, $middleware);
    }

    public function group(string $prefix, callable $callback)
    {
        $previousPrefix = $this->prefix;
        $this->prefix = trim($previousPrefix . '/' . trim($prefix, '/'), '/');

        $callback($this);

        $this->prefix = $previousPrefix;
    }

    protected function addRoute(string $method, string $uri, array $action, array $middleware = [])
    {
        $normalizedUri = trim($this->prefix . '/' . trim($uri, '/'), '/');
        $this->routes[$method][$normalizedUri] = [
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $uri)
    {
        $uri = $this->normalizeUri($uri);

        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            [$class, $methodName] = $route['action'];
            $middlewares = $route['middleware'];

            // Handle middleware
            foreach ($middlewares as $middlewareClass) {
                if (class_exists($middlewareClass)) {
                    $middleware = new $middlewareClass();
                    if (method_exists($middleware, 'handle')) {
                        $middleware->handle();
                    }
                }
            }

            if (class_exists($class)) {
                $controller = new $class();
                if (method_exists($controller, $methodName)) {
                    return $controller->$methodName();
                }

                http_response_code(500);
                echo "Method $methodName not found in controller " . get_class($controller);
            } else {
                http_response_code(500);
                echo "Controller class $class not found.";
            }
        } else {
            http_response_code(404);
            echo "Route $uri not defined.";
        }
    }

    protected function normalizeUri(string $uri): string
    {
        return trim(parse_url($uri, PHP_URL_PATH), '/');
    }
}



/**
 * Old Style Router
 */
// class Router
// {
//     protected $routes = [];

//     public function get($uri, $action)
//     {
//         $this->routes['GET'][$uri] = $action;
//     }

//     public function dispatch($method, $uri)
//     {
//         $uri = trim(parse_url($uri, PHP_URL_PATH), '/');

//         if (isset($this->routes[$method][$uri])) {
//             [$module, $controller, $methodName] = explode('@', $this->routes[$method][$uri]);

//             $class = "App\\Modules\\$module\\Controllers\\{$controller}";

//             if (class_exists($class)) {
//                 $instance = new $class();

//                 if (method_exists($instance, $methodName)) {
//                     return $instance->$methodName();
//                 }

//                 http_response_code(500);
//                 echo "Method $methodName not found.";
//             } else {
//                 http_response_code(500);
//                 echo "Controller $class not found.";
//             }
//         } else {
//             http_response_code(404);
//             echo "Route $uri not defined.";
//         }
//     }
// }
