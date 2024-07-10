<?php

namespace Hexlet\Code\Urls\Database;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class DbUrls
{
    protected \PDO $db;

    public function __construct(Connect $db)
    {
        $this->db = $db->get();
    }

    public function findById(int $value)
    {
        $sql = 'SELECT *
                FROM urls
                WHERE id = :val';
        $sth = $this->db->prepare($sql);
        $sth->execute(['val' => $value]);
        $red = $sth->fetch();
        return $red;
    }

    public function findByUrl(string $value)
    {
        $sql = 'SELECT *
                FROM urls
                WHERE name = :val';
        $sth = $this->db->prepare($sql);
        $sth->execute(['val' => $value]);
        $red = $sth->fetchColumn();
        return $red;
    }

    public function getUrlChecks(int $urlId)
    {
        $sql = 'SELECT *
                FROM url_checks
                WHERE url_id = :id
                ORDER BY created_at DESC';
        $sth = $this->db->prepare($sql);
        $sth->execute(['id' => $urlId]);
        return $sth->fetchAll();
    }

    public function getUrls()
    {
        $firstReq = 'SELECT id,
                    name
                FROM urls
                ORDER BY id DESC';
        $secondReq = 'SELECT checks.url_id, 
                        checks.created_at as last_check, 
                        checks.status_code
                    FROM url_checks as checks
                    WHERE checks.created_at = (
                            SELECT MAX(created_at)
                            FROM url_checks
                            WHERE url_id = checks.url_id
                        )';
        $sth = $this->db->prepare($firstReq);
        $sth->execute();
        $allUrls = $sth->fetchAll();
        $sth = $this->db->prepare($secondReq);
        $sth->execute();
        $lastUrlChecks = $sth->fetchAll();
        return Arr::map($allUrls, function ($url) use ($lastUrlChecks) {
            $needed = Arr::first($lastUrlChecks, function ($value) use ($url) {
                return $value->url_id === $url->id;
            });
            return (object) array_merge((array) $url, (array) $needed);
        });
    }

    public function insertCheck(array $params)
    {
        $paramsWithCurrentDate = array_merge($params, ['created_at' => Carbon::now()]);
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
        $sth->execute($paramsWithCurrentDate);
    }

    public function insertUrl(string $url)
    {
        $sql = 'INSERT INTO urls (name, created_at)
                VALUES (:name, :created_at)';
        $sth = $this->db->prepare($sql);
        $sth->execute([
            'name' => $url,
            'created_at' => Carbon::now(),
        ]);
        return $this->db->lastInsertId();
    }
}
