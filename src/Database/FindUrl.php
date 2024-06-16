<?php

namespace Hexlet\Code\Database;

/**
 * Создание в PostgreSQL таблицы из демонстрации PHP
 */
class FindUrl
{
    /**
     * объект PDO
     * @var \PDO
     */
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function process(int $value, $byKey = 'id')
    {
        $sql = 'SELECT *
                FROM urls
                WHERE :cond = :val';
        $sth = $this->pdo->prepare($sql);
        $sth->execute(['cond' => $byKey, 'val' => $value]);
        $red = $sth->fetchAll();
        return $red;
    }
}
