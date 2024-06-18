<?php

namespace Hexlet\Code\Urls;

use Hexlet\Code\UrlRecord;

class GetCheckRecords
{
    public static function process($db, $urlId)
    {
        $sql = 'SELECT *
                FROM url_checks
                WHERE url_id = :id
                ORDER BY created_at DESC';
        $sth = $db->prepare($sql);
        $sth->execute(['id' => $urlId]);
        $checkRecords = array_map(fn($rec) => new UrlRecord($rec), $sth->fetchAll());
        return $checkRecords;
    }
}
