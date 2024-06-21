<?php

namespace Hexlet\Code\UrlsDatabase;

class FindByUrl
{
    public static function process(\PDO $db, string $value)
    {
        try {
            $sql = 'SELECT id
                    FROM urls
                    WHERE name = :val';
            $sth = $db->prepare($sql);
            $sth->execute(['val' => $value]);
            $red = $sth->fetchColumn();
            return $red;
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
