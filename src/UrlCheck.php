<?php

namespace Hexlet\Code;

use Hexlet\Code\Url;

class UrlCheck extends Url
{
    private int $urlId;
    private ?int $statusCode;
    private ?string $htmlBody;
    private ?string $h1;
    private ?string $title;
    private ?string $description;

    public function __construct(Url|array $checkRecord)
    {
        if ($checkRecord instanceof Url) {
            $this->urlId = $checkRecord->getId();
            $this->name = $checkRecord->getName();
        } else {
            $this->id = $checkRecord['id'];
            $this->urlId = $checkRecord['url_id'];
            $this->createdAt = $checkRecord['created_at'];
            $this->statusCode = $checkRecord['status_code'];
            $this->h1 = $checkRecord['h1'];
            $this->title = $checkRecord['title'];
            $this->description = $checkRecord['description'];
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
        return '';
    }

    public function getHtmlBody()
    {
        if (isset($this->htmlBody)) {
            return $this->htmlBody;
        }
        return '';
    }

    public function getH1()
    {
        if (isset($this->h1)) {
            return $this->h1;
        }
        return '';
    }

    public function getTitle()
    {
        if (isset($this->title)) {
            return $this->title;
        }
        return '';
    }

    public function getDescription()
    {
        if (isset($this->description)) {
            return $this->description;
        }
        return '';
    }

    public function setHtmlBody(string $string)
    {
        $this->htmlBody = $string;
    }

    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;
    }

    public function setH1(string $h1)
    {
        $this->h1 = $h1;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}
