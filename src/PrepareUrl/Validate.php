<?php

namespace Hexlet\Code\PrepareUrl;

use Valitron\Validator;

class Validate
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
            return [$e->getMessage()];
        }
    }
}
