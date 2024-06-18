<?php

namespace Hexlet\Code;

class UrlRecord
{
    private $id;
    private $name;
    private $urlId;
    private $statusCode;
    private $h1;
    private $title;
    private $description;
    private $createdAt;
    private $lastCheck;

    public function __construct(array $record)
    {
        $this->id = $record['id'];
        $this->name = $record['name'] ?? null;
        $this->urlId = $record['url_id'] ?? null;
        $this->statusCode = $record['status_code'] ?? null;
        $this->h1 = $record['h1'] ?? null;
        $this->title = $record['title'] ?? null;
        $this->description = $record['description'] ?? null;
        $this->createdAt = $record['created_at'] ?? null;
        $this->lastCheck = $record['last_check'] ?? null;
    }

    public function __get($key)
    {
        return $this->$key;
    }
}
