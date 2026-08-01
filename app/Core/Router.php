<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];
    public function get(string $path, callable|array $handler, array $middleware = []): void { $this->add('GET', $path, $handler, $middleware); }
    public function post(string $path, callable|array $handler, array $middleware = []): void { $this->add('POST', $path, $handler, $middleware); }
    public function add(string $method, string $path, callable|array $handler, array $middleware = []): void
    {
        $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $path);
        preg_match_all('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', $path, $names);
        $this->routes[$method][] = ['pattern' => '#^' . $pattern . '/?$#', 'names' => $names[1], 'handler' => $handler, 'middleware' => $middleware];
    }
    public function dispatch(string $method, string $uri, callable $resolver): mixed
    {
        $path = rawurldecode(parse_url($uri, PHP_URL_PATH) ?: '/');
        foreach ($this->routes[$method] ?? [] as $route) {
            if (!preg_match($route['pattern'], $path, $matches)) continue;
            array_shift($matches); $params = array_combine($route['names'], $matches) ?: [];
            foreach ($route['middleware'] as $middleware) { $middleware(); }
            return $resolver($route['handler'], $params);
        }
        http_response_code(404); return View::render('errors/404', ['title' => 'Page not found']);
    }
}
