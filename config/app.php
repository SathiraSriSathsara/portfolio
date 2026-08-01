<?php
declare(strict_types=1);

return [
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),
    'url' => rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/'),
    'key' => $_ENV['APP_KEY'] ?? '',
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1', 'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
        'database' => $_ENV['DB_DATABASE'] ?? 'portfolio', 'username' => $_ENV['DB_USERNAME'] ?? '',
        'password' => $_ENV['DB_PASSWORD'] ?? '', 'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    ],
    'profile' => [
        'name' => 'Sathira Sri Sathsara', 'title' => 'Software Engineer / Backend Developer',
        'bio' => 'I design dependable backend systems and thoughtful web experiences, with a focus on clean architecture, security, and performance.',
        'company' => 'Independent', 'location' => 'Sri Lanka', 'timezone' => 'Asia/Colombo',
        'email' => 'hello@example.com', 'website' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
        'github' => 'https://github.com/', 'linkedin' => 'https://linkedin.com/',
        'stack' => ['PHP', 'MySQL', 'JavaScript', 'Docker', 'Git'],
    ],
];
