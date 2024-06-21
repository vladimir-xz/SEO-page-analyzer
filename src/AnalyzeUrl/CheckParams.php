<?php

namespace Hexlet\Code\AnalyzeUrl;

use DiDom\Document;
use Illuminate\Support\Arr;
use Hexlet\Code\UrlCheckRecord;
use Hexlet\Code\AnalyzeUrl\CurlHelper;

class CheckParams
{
    private static array $analyzeParams = [
        'H1' => 'h1',
        'Title' => 'title',
        'Description' => 'meta[name=description]'
    ];

    public static function process(UrlCheckRecord $url)
    {
        if (!$url->getHtmlBody()) {
            $htmlBody = CurlHelper::getHtml($url->getName());
            $url->setHtmlBody($htmlBody);
        }
        try {
            $document = new Document($url->getHtmlBody());
            Arr::map(self::$analyzeParams, function ($value, $key) use ($document, $url) {
                if (count($elements = $document->find($value)) == 0) {
                    return;
                }
                if ($key === 'Description') {
                    $result = optional($elements[0])->content;
                } else {
                    $result = optional($elements[0])->text();
                }
                $command = 'set' . $key;
                $url->$command($result);
            });
        } catch (\Exception $e) {
            // solving problem
            return $e->getMessage();
        }
        return $url;
    }
}
