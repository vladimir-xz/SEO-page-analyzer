<?php

namespace Hexlet\Code\Urls;

use Valitron\Validator;

class UrlValidator
{
    public static function validate(string $url): array
    {
        $v = new Validator(['url' => $url]);
        $v->rule('required', 'url')->message('URL не должен быть пустым');
        $v->rule('url', 'url')->message('Некорректный URL');
        $v->rule('url', 'lengthMax', 255)->message('Некорректный URL');

        if (!$v->validate()) {
            return ['errors' => $v->errors()['url'] ?? []];
        }

        return ['url' => $url];
    }
}
