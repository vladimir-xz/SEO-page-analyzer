<?php

namespace Hexlet\Code\UrlsDatabase;

use Hexlet\Code\UrlCheck;

class GetCheckRecords
{
    public static function process(\PDO $db, int $urlId)
    {
        try {
            $sql = 'SELECT *
                    FROM url_checks
                    WHERE url_id = :id
                    ORDER BY created_at DESC';
            $sth = $db->prepare($sql);
            $sth->execute(['id' => $urlId]);
            $checkRecords = array_map(fn($rec) => new UrlCheck($rec), $sth->fetchAll());
            return $checkRecords;
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
