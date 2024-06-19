<?php

namespace Hexlet\Code\UrlsDatabase;

use Carbon\Carbon;
use Hexlet\Code\UrlRecord;

class InsertUrl
{
    public static function process($db, string $url)
    {
        $sql = 'INSERT INTO urls (name, created_at)
                VALUES (:name, :created_at)';
        $sth = $db->prepare($sql);
        $sth->execute([
            'name' => $url,
            'created_at' => Carbon::now(),
        ]);
        return $db->lastInsertId();
    }
}
