<?php

namespace Hexlet\Code\Urls\Analyze;

use Hexlet\Code\Urls\UrlCheck;
use Illuminate\Support\Str;

class Engine
{
    private array $analyzers;

    public function __construct(string ...$analyzers)
    {
        $analyzersClasses = array_map(function ($name) {
            $properName = Str::of($name)->camel()->ucfirst();
            $actionClass = 'Hexlet\\Code\\Urls\\Analyze\\' . $properName;
            return new $actionClass();
        }, $analyzers);
        $this->analyzers = $analyzersClasses;
    }

    public function process(\stdClass $url)
    {
        $urlCheck = new UrlCheck($url);
        try {
            foreach ($this->analyzers as $method) {
                $method->process($urlCheck);
            }
            return $urlCheck;
        } catch (\Exception $e) {
            return $urlCheck;
        }
    }
}
