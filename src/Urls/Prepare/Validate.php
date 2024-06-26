<?php

namespace Hexlet\Code\Url\Prepare;

use Valitron\Validator;

class Validate
{
    private static array $rules = [
        'required' => [
            'url'
        ],
        'url' => [
            ['url']
        ],
        'lengthMax' => [
            ['url', 255]
        ]
    ];
    private static array $errorMessages = [
        'Url is required' => 'URL не должен быть пустым',
        'Url is not a valid URL' => 'Некорректный URL',
    ];

    public static function validate(string $url): string|null
    {
        try {
            $v = new Validator(['url' => $url]);
            $v->rules(self::$rules);
            if ($v->validate()) {
                return null;
            }
            $firstError = $v->errors()['url'][0];
            return self::$errorMessages[$firstError];
        } catch (\Exception $e) {
            return [$e->getMessage()];
        }
    }
}
