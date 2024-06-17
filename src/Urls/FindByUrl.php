<?php

namespace Hexlet\Code\Urls;

class FindByUrl
{
    public static function process($db, mixed $value)
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
