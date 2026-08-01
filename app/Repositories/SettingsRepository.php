<?php
declare(strict_types=1);
namespace App\Repositories;
use PDO;
final class SettingsRepository
{
    public function __construct(private readonly PDO $pdo) {}
    public function allPublic(): array { return $this->all(true); }
    public function all(bool $publicOnly = false): array
    {
        $sql = 'SELECT setting_key, setting_value, setting_type FROM settings' . ($publicOnly ? ' WHERE is_public = 1' : '');
        try { $rows = $this->pdo->query($sql)->fetchAll(); } catch (\PDOException) { return []; }
        $result = []; foreach ($rows as $row) $result[$row['setting_key']] = $this->cast($row['setting_value'], $row['setting_type']); return $result;
    }
    public function save(array $settings): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO settings (setting_key, setting_value, setting_type, is_public, updated_at) VALUES (?, ?, ?, 1, NOW()) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value), updated_at=NOW()');
        foreach ($settings as $key => $value) $stmt->execute([$key, (string) $value, 'string']);
    }
    private function cast(string $value, string $type): mixed { return match ($type) { 'boolean' => filter_var($value, FILTER_VALIDATE_BOOL), 'integer' => (int) $value, 'json' => json_decode($value, true) ?? [], default => $value }; }
}
