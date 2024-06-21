<?php

namespace Hexlet\Code\UrlsDatabase;

use Hexlet\Code\Url;

class FindByUrl
{
    public static function process(\PDO $db, string $value)
    {
        $sql = 'SELECT id
                FROM urls
                WHERE name = :val';
        $sth = $db->prepare($sql);
        $sth->execute(['val' => $value]);
        $red = $sth->fetchColumn();
        return $red;
    }
}
