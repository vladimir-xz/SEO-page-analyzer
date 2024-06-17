<?php

namespace Hexlet\Code\Urls;

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
        return $red;
    }
}
