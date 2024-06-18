<?php

namespace Hexlet\Code\Urls;

use Carbon\Carbon;
use Hexlet\Code\UrlRecord;

class InsertCheck
{
    public static function process($db, array $url)
    {
        ['url_id' => $urlId, 'status_code' => $statusCode] = $url;
        $sql = 'INSERT INTO url_checks (url_id, status_code, created_at)
                VALUES (:url_id, :status_code, :created_at)';
        $sth = $db->prepare($sql);
        $sth->execute([
            'url_id' => $urlId,
            'status_code' => $statusCode,
            'created_at' => Carbon::now(),
        ]);
        return;
    }
}
