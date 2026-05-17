<?php

namespace App\Core;

use App\Middlewares\AuthMiddleware;
use App\Middlewares\PermissionMiddleware;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action, array $middlewares = []): void
    {
        $this->add('GET', $uri, $action, $middlewares);
    }

    public function post(string $uri, array $action, array $middlewares = []): void
    {
        $this->add('POST', $uri, $action, $middlewares);
    }

    private function add(string $method, string $uri, array $action, array $middlewares): void
    {
        $this->routes[] = compact('method', 'uri', 'action', 'middlewares');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['uri'], $path);
            if ($params === null) {
                continue;
            }

            $this->runMiddlewares($route['middlewares']);
            [$controller, $methodName] = $route['action'];
            (new $controller())->{$methodName}(...$params);
            return;
        }

        http_response_code(404);
        require base_path('views/errors/404.php');
    }

    private function match(string $routeUri, string $requestPath): ?array
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '([^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';
        if (!preg_match($pattern, $requestPath, $matches)) {
            return null;
        }

        array_shift($matches);
        return $matches;
    }

    private function runMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            if ($middleware === 'auth') {
                (new AuthMiddleware())->handle();
                continue;
            }

            if (str_starts_with($middleware, 'role:')) {
                $roles = explode('|', substr($middleware, 5));
                (new PermissionMiddleware())->handle($roles);
            }
        }
    }
}
