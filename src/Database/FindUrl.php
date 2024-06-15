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
    private $byKey;

    public function __construct($pdo, $byKey = 'id')
    {
        $this->pdo = $pdo;
        $this->byKey = $byKey;
    }

    public function process(int $value)
    {
        $sql = 'SELECT ad_id, client_id, image_url
                FROM ad
                WHERE :cond = :val';
        $sth = $this->pdo->prepare($sql);
        $sth->execute(['cond' => $this->byKey, 'val' => $value]);
        $red = $sth->fetchAll();
        return $red;
    }
}
