<?php

namespace Hexlet\Code\UrlsDatabase;

use Hexlet\Code\UrlRecord;

class FindById
{
    public static function process($db, mixed $value)
    {
        $sql = 'SELECT *
                FROM urls
                WHERE id = :val';
        $sth = $db->prepare($sql);
        $sth->execute(['val' => $value]);
        $red = $sth->fetch();
        return new UrlRecord($red);
    }
}
