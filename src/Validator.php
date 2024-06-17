<?php

namespace Hexlet\Code;

class Validator
{
    public function validate(array $url): array
    {
        $errors = [];
        return [];
        if (strlen($url['name']) > 255) {
            $errors['strlen'] = "Url must be less than 256 characters";
        } elseif (!filter_var($url['name'], FILTER_VALIDATE_URL)) {
            $errors['valid'] = 'URL is not valid';
        }
        return $errors;
    }
}
