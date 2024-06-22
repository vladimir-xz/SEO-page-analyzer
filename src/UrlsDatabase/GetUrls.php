<?php

namespace Hexlet\Code\UrlsDatabase;

class GetUrls
{
    public static function process(\PDO $db)
    {
        try {
            $sql = 'SELECT urls.id,
                        urls.name,
                        url_checks.created_at as last_check,
                        url_checks.status_code
                    FROM urls
                    LEFT JOIN (
                        SELECT checks.url_id, 
                            checks.created_at, 
                            checks.status_code
                        FROM url_checks as checks
                        WHERE checks.created_at = (
                                SELECT MAX(created_at)
                                FROM url_checks
                                WHERE url_id = checks.url_id
                            ) 
                    ) url_checks
                    ON url_checks.url_id = urls.id
                    ORDER BY urls.id DESC';
            $sth = $db->prepare($sql);
            $sth->execute();
            return $sth->fetchAll();
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
