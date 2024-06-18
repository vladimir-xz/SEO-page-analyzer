<?php

namespace Hexlet\Code;

class UrlCheckRecord
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

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    public function setH1($h1)
    {
        $this->h1 = $h1;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}
