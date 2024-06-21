<?php

namespace Hexlet\Code;

use Hexlet\Code\UrlRecord;

class UrlCheckRecord extends UrlRecord
{
    private ?int $urlId;
    private ?int $statusCode;
    private ?string $htmlBody;
    private ?string $h1;
    private ?string $title;
    private ?string $description;
    private ?string $lastCheck;

    public function __construct(UrlRecord|array $checkRecord)
    {
        if ($checkRecord instanceof UrlRecord) {
            $this->urlId = $checkRecord->getId();
            $this->name = $checkRecord->getName();
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

    public function getUrlId()
    {
        return $this->urlId;
    }

    public function getStatusCode()
    {
        if (isset($this->statusCode)) {
            return $this->statusCode;
        }
        return null;
    }

    public function getHtmlBody()
    {
        if (isset($this->htmlBody)) {
            return $this->htmlBody;
        }
        return null;
    }

    public function getH1()
    {
        if (isset($this->h1)) {
            return $this->h1;
        }
        return null;
    }

    public function getTitle()
    {
        if (isset($this->title)) {
            return $this->title;
        }
        return null;
    }

    public function getDescription()
    {
        if (isset($this->description)) {
            return $this->description;
        }
        return null;
    }

    public function getLastCheck()
    {
        if (isset($this->lastCheck)) {
            return $this->lastCheck;
        }
        return null;
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
