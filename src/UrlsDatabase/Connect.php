<?php

namespace Hexlet\Code\UrlsDatabase;

class Connect
{
    private static ?Connect $conn = null;

    public function connect()
    {
        try {
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
            $pdo = new \PDO($conStr);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function get()
    {
        if (null === self::$conn) {
            self::$conn = new self();
        }

        return self::$conn;
    }
}
