<?php

namespace Hexlet\Code\Url\Prepare;

class Normalize
{
    public static function process(string $url): string
    {
        $urlLowKey = mb_strtolower($url);
        $urlParts = parse_url($urlLowKey);
        return $urlParts['scheme'] . '://' . $urlParts['host'];
    }
}
