<?php

namespace PostgreSQLTutorial;

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
    private $condition;

    public function __construct($pdo, $condition = 'id')
    {
        $this->pdo = $pdo;
        $this->condition = $condition;
    }

    public function find(int $value)
    {
        $sql = 'SELECT ad_id, client_id, image_url
                FROM ad
                WHERE :cond = :val';
        $sth = $this->pdo->prepare($sql);
        $sth->execute(['cond' => $this->condition, 'val' => $value]);
        $red = $sth->fetchAll();
        return $red;
    }
}
