<?php

namespace Hexlet\Code;

class UrlCheck
{
    private int $urlId;
    private string $name;
    private ?int $statusCode;
    private ?string $htmlBody;
    private ?string $h1;
    private ?string $title;
    private ?string $description;

    public function __construct(object $checkRecord)
    {
        $this->urlId = $checkRecord->id;
        $this->name = $checkRecord->name;
    }

    public function getUrlId()
    {
        return $this->urlId;
    }

    public function getName()
    {
        return $this->name;
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
