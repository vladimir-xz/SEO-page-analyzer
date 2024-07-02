<?php

namespace Hexlet\Code\Urls\Database;

use Carbon\Carbon;
use Hexlet\Code\Urls\UrlCheck;

class DbUrls
{
    protected \PDO $db;

    public function __construct(Connect $db)
    {
        $this->db = $db->get();
    }

    public function findById(int $value)
    {
        try {
            $sql = 'SELECT *
                    FROM urls
                    WHERE id = :val';
            $sth = $this->db->prepare($sql);
            $sth->execute(['val' => $value]);
            $red = $sth->fetch();
            return $red;
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }

    public function findByUrl(string $value)
    {
        try {
            $sql = 'SELECT id
                    FROM urls
                    WHERE name = :val';
            $sth = $this->db->prepare($sql);
            $sth->execute(['val' => $value]);
            $red = $sth->fetchColumn();
            return $red;
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }

    public function getCheckRecords(int $urlId)
    {
        try {
            $sql = 'SELECT *
                    FROM url_checks
                    WHERE url_id = :id
                    ORDER BY created_at DESC';
            $sth = $this->db->prepare($sql);
            $sth->execute(['id' => $urlId]);
            return $sth->fetchAll();
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }

    public function getUrls()
    {
        try {
            $sql = 'SELECT urls.id,
                        urls.name,
                        url_checks.created_at as last_check,
                        url_checks.status_code
                    FROM urls
                    LEFT JOIN (
                        SELECT checks.url_id, 
                            checks.created_at, 
                            checks.status_code
                        FROM url_checks as checks
                        WHERE checks.created_at = (
                                SELECT MAX(created_at)
                                FROM url_checks
                                WHERE url_id = checks.url_id
                            ) 
                    ) url_checks
                    ON url_checks.url_id = urls.id
                    ORDER BY urls.id DESC';
            $sth = $this->db->prepare($sql);
            $sth->execute();
            return $sth->fetchAll();
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }

    public function insertCheck(UrlCheck $url)
    {
        try {
            $sql = 'INSERT INTO url_checks (
                url_id,
                status_code,
                h1,
                title,
                description,
                created_at)
                    VALUES (
                    :url_id,
                    :status_code,
                    :h1,
                    :title,
                    :description,
                    :created_at)';
            $sth = $this->db->prepare($sql);
            $sth->execute([
                'url_id' => $url->getUrlId(),
                'status_code' => $url->getStatusCode(),
                'h1' => $url->getH1(),
                'title' => $url->getTitle(),
                'description' => $url->getDescription(),
                'created_at' => Carbon::now(),
            ]);
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }

    public function insertUrl(string $url)
    {
        try {
            $sql = 'INSERT INTO urls (name, created_at)
                    VALUES (:name, :created_at)';
            $sth = $this->db->prepare($sql);
            $sth->execute([
                'name' => $url,
                'created_at' => Carbon::now(),
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        }
    }
}
