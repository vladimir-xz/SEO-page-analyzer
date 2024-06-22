<?php

namespace Hexlet\Code\PrepareUrl;

class Normalize
{
    public static function process(string $url): string
    {
        $urlParts = parse_url($url);
        return $urlParts['scheme'] . '://' . $urlParts['host'];
    }
}
