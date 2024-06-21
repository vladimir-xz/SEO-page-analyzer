<?php

namespace Hexlet\Code\UrlsDatabase;

use Hexlet\Code\UrlCheckRecord;

class GetCheckRecords
{
    public static function process(\PDO $db, int $urlId)
    {
        $sql = 'SELECT *
                FROM url_checks
                WHERE url_id = :id
                ORDER BY created_at DESC';
        $sth = $db->prepare($sql);
        $sth->execute(['id' => $urlId]);
        $checkRecords = array_map(fn($rec) => new UrlCheckRecord($rec), $sth->fetchAll());
        return $checkRecords;
    }
}
