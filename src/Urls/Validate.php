<?php

namespace Hexlet\Code\Urls;

use Valitron\Validator;

class Validate
{
    private static function normalize(string $url): string
    {
        if (!$url) {
            return $url;
        }
        $urlLowKey = mb_strtolower($url);
        $urlParts = parse_url($urlLowKey);
        $scheme = $urlParts['scheme'] ?? '';
        $host = $urlParts['host'] ?? '';
        return $scheme . '://' . $host;
    }

    public static function validate(string $url): string|array
    {
        $normalizedUrl = self::normalize($url);

        $v = new Validator(['url' => $normalizedUrl]);
        $v->rule('required', 'url')->message('URL не должен быть пустым');
        $v->rule('url', 'url')->message('Некорректный URL');
        $v->rule('url', 'lengthMax', 255)->message('Некорректный URL');

        if (!$v->validate()) {
            return $v->errors()['url'] ?? [];
        }

        return $normalizedUrl;
    }
}
