<?php

namespace Hexlet\Code\AnalyzeUrl;

use Hexlet\Code\UrlCheckRecord;

class Engine
{
    private $analyzers;

    public function __construct(...$analyzers)
    {
        $analyzersClasses = array_map(function ($name) {
            $actionClass = 'Hexlet\\Code\\AnalyzeUrl\\' . $name;
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
