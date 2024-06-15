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
        $find = new FindUrl($this->pdo, 'url');
        $findResult = $find->process($url);
        if ($findResult) {
            return $findResult;
        }
        $sql = 'INSERT INTO ad (ad_id, client_id, image_url)
                VALUES (:ad_id, :client_id, ;image_url)';
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            'ad_id' => $url,
            'client_id' => 444,
            'image_url' => 'dodo.jpg'
        ]);
        return $this->pdo->lastInsertId('labels_id_seq');
    }
}
