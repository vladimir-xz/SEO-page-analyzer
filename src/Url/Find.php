<?php

namespace Hexlet\Code\Url;

/**
 * Создание в PostgreSQL таблицы из демонстрации PHP
 */
class Find
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

    public function process(mixed $value)
    {
        $sql = 'SELECT *
                FROM urls
                WHERE id = :val';
        $sth = $this->pdo->prepare($sql);
        $sth->execute(['val' => $value]);
        $red = $sth->fetch();
        return $red;
    }
}
