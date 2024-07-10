<?php

namespace Hexlet\Code\Urls;

use Valitron\Validator;

class Validate
{
    private static function normalize(string $url): string
    {
        $urlLower = mb_strtolower($url);
        $urlParts = parse_url($urlLower);
        $scheme = $urlParts['scheme'] ?? '';
        $host = $urlParts['host'] ?? '';
        return $scheme . '://' . $host;
    }

    public static function validate(string $url): array
    {
        $v = new Validator(['url' => $url]);
        $v->rule('required', 'url')->message('URL не должен быть пустым');
        $v->rule('url', 'url')->message('Некорректный URL');
        $v->rule('url', 'lengthMax', 255)->message('Некорректный URL');

        if (!$v->validate()) {
            return ['error' => $v->errors()['url'] ?? []];
        }

        $normalizedUrl = self::normalize($url);

        return ['url' => $normalizedUrl];
    }
}
