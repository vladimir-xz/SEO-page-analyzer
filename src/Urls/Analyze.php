<?php

namespace Hexlet\Code\Urls;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use DiDom\Document;
use Illuminate\Support\Arr;
use Hexlet\Code\Urls\UrlCheck;

class Analyze
{
    private \Hexlet\Code\Urls\UrlCheckFactory $urlCheckFactory;
    private static array $analyzeParams = [
        'H1' => 'h1',
        'Title' => 'title',
        'Description' => 'meta[name=description]'
    ];

    public function __construct(\Hexlet\Code\Urls\UrlCheckFactory $urlCheckFactory)
    {
        $this->urlCheckFactory = $urlCheckFactory;
    }

    public function checkUrl(\stdClass $url)
    {
        $urlCheck = $this->urlCheckFactory->create($url);
        $this->checkConnection($urlCheck);
        $this->checkParams($urlCheck);
        return $urlCheck;
    }

    private function checkConnection(UrlCheck $url)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url->getName(), ['connect_timeout' => 3.14, 'http_errors' => false]);
            $status = $res->getStatusCode();
            $url->setHtmlBody($res->getBody()->__toString());
            $url->setStatusCode($status);
        } catch (TransferException $e) {
            echo $e->getMessage();
            //throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    private function checkParams(UrlCheck $url)
    {
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
            echo $e->getMessage();
        }
    }
}
