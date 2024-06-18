<?php

namespace Hexlet\Code;

class UrlCheck
{
    private $id;
    private $urlId;
    private $statusCode;
    private $h1;
    private $title;
    private $description;
    private $createdAt;

    public function __construct(array $checkRecord)
    {
        $this->id = $checkRecord['id'];
        $this->urlId = $checkRecord['url_id'];
        $this->statusCode = $checkRecord['status_code'] ?? null;
        $this->h1 = $checkRecord['h1'] ?? null;
        $this->title = $checkRecord['title'] ?? null;
        $this->description = $checkRecord['description'] ?? null;
        $this->createdAt = $checkRecord['created_at'] ?? null;
    }

    public function __get($key)
    {
        return $this->$key;
    }

    public function __set(string $key, string|int $value)
    {
        return $this->$key = $value;
    }
}
