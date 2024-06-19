<?php

namespace Hexlet\Code\UrlsDatabase;

class Connect
{
    private static ?Connect $conn = null;

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
            ltrim($databaseUrl['path'], '/'),
            $databaseUrl['user'],
            $databaseUrl['pass']
        );

        // $conStr = "pgsql:host=localhost;port=5432;dbname=mydb;user=vova_xz;password=gnom";

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
