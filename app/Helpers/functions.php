<?php
declare(strict_types=1);

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string { return rtrim((string) ($_ENV['APP_URL'] ?? 'http://localhost:8000'), '/') . '/' . ltrim($path, '/'); }
function asset(string $path): string { return url('assets/' . ltrim($path, '/')); }
function csrf_field(): string { return '<input type="hidden" name="_token" value="' . e(\App\Core\Csrf::token()) . '">'; }
function old(string $key, string $default = ''): string { return e((string) ($_SESSION['_old'][$key] ?? $default)); }
