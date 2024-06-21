<?php

namespace Hexlet\Code\UrlsDatabase;

use Hexlet\Code\Url;

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
            if ($red) {
                return new Url($red);
            }
            return $red;
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
