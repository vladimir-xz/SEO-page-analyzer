<?php

namespace Hexlet\Code\Urls;

use Carbon\Carbon;

class InsertCheck
{
    public static function process($db, array $url)
    {
        ['url_id' => $urlId] = $url;
        $sql = 'INSERT INTO url_checks (url_id, created_at)
                VALUES (:name, :created_at)';
        $sth = $db->prepare($sql);
        $sth->execute([
            'name' => $urlId,
            'created_at' => Carbon::now(),
        ]);
        return $db->lastInsertId();
    }
}
