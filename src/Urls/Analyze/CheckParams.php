<?php

namespace Hexlet\Code\Urls\Analyze;

use DiDom\Document;
use Illuminate\Support\Arr;
use Hexlet\Code\Urls\UrlCheck;

class CheckParams
{
    private static array $analyzeParams = [
        'H1' => 'h1',
        'Title' => 'title',
        'Description' => 'meta[name=description]'
    ];

    public static function process(UrlCheck $url)
    {
        if (!$url->getHtmlBody()) {
            $htmlBody = GetHtmlWithCurl::getHtml($url->getName());
            $url->setHtmlBody($htmlBody);
        }
        try {
            $document = new Document($url->getHtmlBody());
            Arr::map(self::$analyzeParams, function ($value, $key) use ($document, $url) {
                if (count($elements = $document->find($value)) == 0) {
                    return;
                } elseif ($key === 'Description') {
                    $result = optional($elements[0])->content;
                } else {
                    $result = optional($elements[0])->text();
                }
                $command = 'set' . $key;
                $url->$command($result);
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
