<?php

namespace Hexlet\Code;

use Valitron\Validator;

class ValidateUrl
{
    private static array $rules = [
        'url' => [
            ['url']
        ],
        'lengthMax' => [
            ['url', 255]
        ]
        ];

    public static function validate(string $url): array
    {
        try {
            $v = new Validator(['url' => $url]);
            $v->rules(self::$rules);
            if ($v->validate()) {
                return [];
            }
            return $v->errors();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
