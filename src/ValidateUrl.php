<?php

namespace Hexlet\Code;

use Valitron\Validator;

class ValidateUrl
{
    private static $rules = [
        'url' => [
            ['url']
        ],
        'lengthMax' => [
            ['url', 255]
        ]
        ];

    public static function validate(string $url): array
    {
        $v = new Validator(['url' => $url]);
        $v->rules(self::$rules);
        if ($v->validate()) {
            return [];
        } else {
            return $v->errors();
        }
    }
}
