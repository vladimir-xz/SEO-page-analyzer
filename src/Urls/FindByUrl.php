<?php

namespace Hexlet\Code\Urls;

/**
 * Создание в PostgreSQL таблицы из демонстрации PHP
 */
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
