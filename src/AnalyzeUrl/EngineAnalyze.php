<?php

namespace Hexlet\Code\AnalyzeUrl;

use Hexlet\Code\UrlCheckRecord;
use Illuminate\Support\Str;

class EngineAnalyze
{
    private $analyzers;

    public function __construct(...$analyzers)
    {
        $analyzersClasses = array_map(function ($name) {
            $properName = Str::of($name)->camel()->ucfirst();
            $actionClass = 'Hexlet\\Code\\AnalyzeUrl\\' . $properName;
            return new $actionClass();
        }, $analyzers);
        $this->analyzers = $analyzersClasses;
    }

    public function process($url)
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
