<?php

namespace Hexlet\Code\Urls\Prepare;

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
        'Url must not exceed 255 characters' => 'URL не должен превышать 255 символов'
    ];

    public static function validate(string $url): ?string
    {
        try {
            $v = new Validator(['url' => $url]);
            $v->rules(self::$rules);
            if ($v->validate()) {
                return null;
            }
            return $v->errors();
            $firstError = $errors['url'][0] ?? '';
            return self::$errorMessages[$firstError];
        } catch (\Exception $e) {
            return [$e->getMessage()];
        }
    }
}
