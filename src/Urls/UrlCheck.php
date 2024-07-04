<?php

namespace Hexlet\Code\Urls;

class UrlCheck
{
    private int $urlId;
    private string $name;
    private ?int $statusCode;
    private ?string $htmlBody;
    private ?string $h1;
    private ?string $title;
    private ?string $description;

    public function __construct(\stdClass $checkRecord)
    {
        $this->urlId = $checkRecord->id;
        $this->name = $checkRecord->name;
    }

    public function getUrlId(): int
    {
        return $this->urlId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatusCode(): ?int
    {
        if (isset($this->statusCode)) {
            return $this->statusCode;
        }
        return null;
    }

    public function getHtmlBody(): ?string
    {
        if (isset($this->htmlBody)) {
            return $this->htmlBody;
        }
        return null;
    }

    public function getH1(): ?string
    {
        if (isset($this->h1)) {
            return $this->h1;
        }
        return null;
    }

    public function getTitle(): ?string
    {
        if (isset($this->title)) {
            return $this->title;
        }
        return null;
    }

    public function getDescription(): ?string
    {
        if (isset($this->description)) {
            return $this->description;
        }
        return null;
    }

    public function setHtmlBody(string $string): void
    {
        $this->htmlBody = $string;
    }

    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    public function setH1(?string $h1): void
    {
        $this->h1 = $h1;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
