<?php
declare(strict_types=1);

namespace App\Core;

use App\Repositories\PostRepository;
use App\Repositories\SettingsRepository;
use App\Services\MarkdownService;

final class App
{
    private Router $router; private Database $database;
    public function __construct(public readonly array $config)
    {
        $this->database = new Database($config['db']); $this->router = new Router();
        $routes = require BASE_PATH . '/routes/web.php'; $routes($this->router);
    }
    public function run(): void
    {
        try {
            $output = $this->router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/', function ($handler, $params) {
                if (is_callable($handler)) return $handler(...array_values($params));
                [$class, $method] = $handler; $controller = new $class($this->database->connection(), new PostRepository($this->database->connection()), new SettingsRepository($this->database->connection()), new MarkdownService(), $this->config);
                return $controller->$method(...array_values($params));
            }); echo $output;
        } catch (\Throwable $e) {
            error_log(sprintf("[%s] %s\n", date(DATE_ATOM), $e)); http_response_code(500);
            echo View::render('errors/500', [
                'title' => 'Something went wrong',
                'debug' => $this->config['debug'] ? $e->getMessage() : null,
                'description' => 'The application could not complete this request.',
                'canonical' => $this->config['url'],
                'profile' => $this->config['profile'],
                'recentPosts' => [],
                'siteUrl' => $this->config['url'],
            ]);
        }
    }
}
