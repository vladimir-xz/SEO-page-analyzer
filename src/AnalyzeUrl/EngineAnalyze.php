<?php

namespace Hexlet\Code\AnalyzeUrl;

use Hexlet\Code\UrlCheck;
use Hexlet\Code\Url;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

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

    public function process(stdClass $url)
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
