<?php

namespace Hexlet\Code;

use Hexlet\Code\UrlRecord;

use function PHPUnit\Framework\isInstanceOf;

class UrlCheckRecord extends UrlRecord
{
    private $urlId;
    private $statusCode;
    private $htmlBody;
    private $h1;
    private $title;
    private $description;
    private $lastCheck;

    public function __construct(UrlRecord|array $checkRecord)
    {
        if ($checkRecord instanceof UrlRecord) {
            $this->urlId = $checkRecord->id;
            $this->name = $checkRecord->name;
        } else {
            $this->id = $checkRecord['id'];
            $this->urlId = $checkRecord['url_id'] ?? null;
            $this->name = $checkRecord['name'] ?? null;
            $this->createdAt = $checkRecord['created_at'] ?? null;
            $this->statusCode = $checkRecord['status_code'] ?? null;
            $this->h1 = $checkRecord['h1'] ?? null;
            $this->title = $checkRecord['title'] ?? null;
            $this->description = $checkRecord['description'] ?? null;
            $this->lastCheck = $checkRecord['last_check'] ?? null;
        }
    }

    public function __get($key)
    {
        return $this->$key;
    }

    public function setHtmlBody($string)
    {
        $this->htmlBody = $string;
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
