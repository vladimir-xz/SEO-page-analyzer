<?php

namespace Hexlet\Code\UrlsDatabase;

class FindById
{
    public static function process(\PDO $db, int $value)
    {
        try {
            $sql = 'SELECT *
                    FROM urls
                    WHERE id = :val';
            $sth = $db->prepare($sql);
            $sth->execute(['val' => $value]);
            $red = $sth->fetch();
            return $red;
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
