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

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo "Route $uri not defined.";
            return;
        }

        foreach ($this->routes[$method] as $routePattern => $route) {
            $matches = [];
            $patternRegex = $this->convertPatternToRegex($routePattern, $matches);

            if (preg_match($patternRegex, $uri, $params)) {
                array_shift($params); // Remove full match

                [$class, $methodName] = $route['action'];
                $middlewares = $route['middleware'];

                // Run middlewares (with param support)
                $this->runMiddlewares($middlewares);

                // Call controller
                if (class_exists($class)) {
                    $controller = new $class();
                    if (method_exists($controller, $methodName)) {
                        return $controller->$methodName(...$params);
                    }

                    http_response_code(500);
                    echo "Method $methodName not found in controller " . get_class($controller);
                    return;
                }

                http_response_code(500);
                echo "Controller class $class not found.";
                return;
            }
        }

        http_response_code(404);
        echo "Route $uri not defined.";
    }

    protected function normalizeUri(string $uri): string
    {
        return trim(parse_url($uri, PHP_URL_PATH), '/');
    }

    protected function convertPatternToRegex(string $pattern, array &$matches = []): string
    {
        $pattern = preg_replace_callback('/\{(\w+)\}/', function ($m) use (&$matches) {
            $matches[] = $m[1];
            return '([^/]+)';
        }, $pattern);

        return '#^' . $pattern . '$#';
    }

    protected function runMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $middlewareClass) {
            $params = null;

            if (str_contains($middlewareClass, ':')) {
                [$middlewareClassName, $params] = explode(':', $middlewareClass, 2);
            } else {
                $middlewareClassName = $middlewareClass;
            }

            if (class_exists($middlewareClassName)) {
                $middleware = $params ? new $middlewareClassName($params) : new $middlewareClassName();
                if (method_exists($middleware, 'handle')) {
                    $middleware->handle();
                }
            }
        }
    }
}
