<?php

namespace Hexlet\Code\Urls\Database;

use Carbon\Carbon;

class InsertUrl
{
    public static function process(\PDO $db, string $url)
    {
        try {
            $sql = 'INSERT INTO urls (name, created_at)
                    VALUES (:name, :created_at)';
            $sth = $db->prepare($sql);
            $sth->execute([
                'name' => $url,
                'created_at' => Carbon::now(),
            ]);
            return $db->lastInsertId();
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
