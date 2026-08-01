<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

final class Database
{
    private ?PDO $pdo = null;
    public function __construct(private readonly array $config) {}
    public function connection(): PDO
    {
        if ($this->pdo) return $this->pdo;
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $this->config['host'], $this->config['port'], $this->config['database'], $this->config['charset']);
        return $this->pdo = new PDO($dsn, $this->config['username'], $this->config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    public function transaction(callable $callback): mixed
    {
        $pdo = $this->connection(); $pdo->beginTransaction();
        try { $result = $callback($pdo); $pdo->commit(); return $result; }
        catch (\Throwable $e) { if ($pdo->inTransaction()) $pdo->rollBack(); throw $e; }
    }
}
