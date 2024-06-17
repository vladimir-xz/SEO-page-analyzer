<?php

namespace Hexlet\Code\Urls;

class GetUrls
{
    public static function process($db)
    {
        $sql = 'SELECT urls.id as id,
                        urls.name as name,
                        (
                            SELECT
                                MAX(created_at)
                                FROM url_checks
                                WHERE urls.id = url_checks.url_id
                                GROUP BY url_id
                        ) as last_check
                FROM urls
                ORDER BY urls.id DESC';
        $sth = $db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }
}
