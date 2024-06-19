<?php

namespace Hexlet\Code\UrlsDatabase;

use Carbon\Carbon;
use Hexlet\Code\UrlCheckRecord;

class InsertCheck
{
    public static function process($db, UrlCheckRecord $url)
    {
        $sql = 'INSERT INTO url_checks (
            url_id,
            status_code,
            h1,
            title,
            description,
            created_at)
                VALUES (
                :url_id,
                :status_code,
                :h1,
                :title,
                :description,
                :created_at)';
        $sth = $db->prepare($sql);
        $sth->execute([
            'url_id' => $url->urlId,
            'status_code' => $url->statusCode,
            'h1' => $url->h1,
            'title' => $url->title,
            'description' => $url->description,
            'created_at' => Carbon::now(),
        ]);
        return;
    }
}

// $sql = 'INSERT INTO url_checks (
//     url_id,
//     status_code,
//     h1,
//     title,
//     description,
//     created_at)
//         VALUES (
//         :url_id,
//         :status_code,
//         :h1,
//         :title,
//         :description,
//         :created_at)';
// $sth = $db->prepare($sql);
// $sth->execute([
//     'url_id' => $url->urlId,
//     'status_code' => $url->statusCode,
//     'h1' => $url->h1,
//     'title' => $url->title,
//     'description' => $url->description,
//     'created_at' => Carbon::now(),
// ]);
// return
