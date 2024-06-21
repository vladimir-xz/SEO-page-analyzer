<?php

namespace Hexlet\Code;

use Hexlet\Code\Url;

class UrlAndCheck extends Url
{
    private ?int $statusCode;
    private ?string $lastCheck;

    public function __construct(array $checkRecord)
    {
        $this->id = $checkRecord['id'];
        $this->name = $checkRecord['name'];
        $this->statusCode = $checkRecord['status_code'] ?? null;
        $this->lastCheck = $checkRecord['last_check'] ?? null;
    }

    public function getStatusCode()
    {
        if (isset($this->statusCode)) {
            return $this->statusCode;
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
}
