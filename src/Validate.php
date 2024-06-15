<?php

namespace App;

class Validator
{
    public function validate(string $url): array
    {
        $errors = [];
        if (strlen($url) > 255) {
            $errors['strlen'] = "Url must be less than 256 characters";
        } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
            $errors['valid'] = 'URL is not valid';
        }
        return $errors;
    }
}
