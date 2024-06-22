<?php

namespace Hexlet\Code\UrlsDatabase;

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
            return $sth->fetchAll();
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
