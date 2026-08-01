<?php
declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string { return $_SESSION['_csrf'] ??= bin2hex(random_bytes(32)); }
    public static function valid(?string $token): bool { return is_string($token) && hash_equals(self::token(), $token); }
    public static function rotate(): void { $_SESSION['_csrf'] = bin2hex(random_bytes(32)); }
}
