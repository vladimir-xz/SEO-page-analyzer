<?php

namespace Hexlet\Code\Urls\Database;

class Connect
{
    private \PDO $conn;

    public function __construct()
    {
        $databaseUrl = parse_url($_ENV['DATABASE_URL']);
        $conStr = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $databaseUrl['host'] ?? '',
            $databaseUrl['port'] ?? '',
            ltrim($databaseUrl['path'] ?? '', '/'),
            $databaseUrl['user'] ?? '',
            $databaseUrl['pass'] ?? ''
        );
        $pdo = new \PDO($conStr);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        $this->conn = $pdo;
    }

    public function get()
    {
        return $this->conn;
    }
}
