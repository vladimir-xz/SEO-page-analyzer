<?php

namespace Hexlet\Code\Urls\Database;

use Carbon\Carbon;
use Hexlet\Code\Urls\UrlCheck;

class InsertCheck
{
    public static function process(\PDO $db, UrlCheck $url)
    {
        try {
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
                'url_id' => $url->getUrlId(),
                'status_code' => $url->getStatusCode(),
                'h1' => $url->getH1(),
                'title' => $url->getTitle(),
                'description' => $url->getDescription(),
                'created_at' => Carbon::now(),
            ]);
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
