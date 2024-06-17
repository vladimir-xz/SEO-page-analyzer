<?php

namespace Hexlet\Code\Urls;

use Hexlet\Code\Database\FindUrl;

class Insert
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

    public function process(string $url)
    {
        $find = new FindByUrl($this->pdo);
        $findResult = $find->process($url);
        if ($findResult) {
            return $findResult;
        }
        $sql = 'INSERT INTO urls (name, created_at)
                VALUES (:name, :created_at)';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            'name' => $url,
            'created_at' => '2022-10-20',
        ]);
        return $this->pdo->lastInsertId();
    }
}
