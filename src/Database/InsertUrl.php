<?php

namespace Hexlet\Code\Database;

use Hexlet\Code\Database\FindUrl;

class InsertUrl
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

    public function process(int $url)
    {
        $find = new FindUrl($this->pdo);
        $findResult = $find->process($url, 'name');
        if ($findResult) {
            return $findResult;
        }
        $sql = 'INSERT INTO urls (name, created_at)
                VALUES (:name, ;created_at)';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            'name' => $url,
            'created_at' => 444,
        ]);
        return $this->pdo->lastInsertId('labels_id_seq');
    }
}
