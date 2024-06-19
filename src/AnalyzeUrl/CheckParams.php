<?php

namespace Hexlet\Code\AnalyzeUrl;

use DiDom\Document;
use Illuminate\Support\Arr;
use Hexlet\Code\UrlCheckRecord;

use function PHPUnit\Framework\isEmpty;

class CheckParams
{
    private static $analyzeParams = [
        'H1' => 'h1',
        'Title' => 'title',
        'Description' => 'meta[name=description]'
    ];

    public static function process(UrlCheckRecord $url)
    {
        $document = new Document($url->name, true);
        Arr::map(self::$analyzeParams, function ($value, $key) use ($document, $url) {
            if (count($elements = $document->find($value)) > 0) {
                if ($key === 'Description') {
                    $result = $elements[0]->content;
                } else {
                    $result = $elements[0]->text();
                }
                $command = 'set' . $key;
                $url->$command($result);
            }
            return;
        });
        return $url;
    }
}
