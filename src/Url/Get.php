<?php

namespace Hexlet\Code\Url;

use PostgreSQLTutorial\FindUrl;

class Get
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

    public function process()
    {
        $sql = 'SELECT *
                FROM urls
                ORDER BY created_at DESC';
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }
}
