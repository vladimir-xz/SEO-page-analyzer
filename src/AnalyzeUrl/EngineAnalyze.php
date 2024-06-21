<?php

namespace Hexlet\Code\AnalyzeUrl;

use Hexlet\Code\UrlCheckRecord;
use Hexlet\Code\UrlRecord;
use Illuminate\Support\Str;

class EngineAnalyze
{
    private array $analyzers;

    public function __construct(string ...$analyzers)
    {
        $analyzersClasses = array_map(function ($name) {
            $properName = Str::of($name)->camel()->ucfirst();
            $actionClass = 'Hexlet\\Code\\AnalyzeUrl\\' . $properName;
            return new $actionClass();
        }, $analyzers);
        $this->analyzers = $analyzersClasses;
    }

    public function process(UrlRecord $url)
    {
        try {
            $urlCheck = new UrlCheckRecord($url);
            foreach ($this->analyzers as $method) {
                $method->process($urlCheck);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return $urlCheck;
    }
}
