<?php

namespace Hexlet\Code\Database;

class ConnectUrls
{
    private static ?ConnectUrls $conn = null;

    public function connect()
    {
        $databaseUrl = parse_url($_ENV['DATABASE_URL']);
        if ($databaseUrl === false) {
            throw new \Exception("Error reading database configuration file");
        }

        $conStr = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $databaseUrl['host'],
            $databaseUrl['port'],
            ltrim($databaseUrl['database'], '/'),
            $databaseUrl['user'],
            $databaseUrl['password']
        );

        $pdo = new \PDO($conStr);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function get()
    {
        if (null === static::$conn) {
            static::$conn = new self();
        }

        return static::$conn;
    }
}
