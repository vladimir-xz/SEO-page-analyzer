<?php

namespace Hexlet\Code\Urls\Helpers;

class Utils
{
    public static function normalize(string $url): string
    {
        $urlLower = mb_strtolower($url);
        $urlParts = parse_url($urlLower);
        $scheme = $urlParts['scheme'] ?? '';
        $host = $urlParts['host'] ?? '';
        return $scheme . '://' . $host;
    }
}
