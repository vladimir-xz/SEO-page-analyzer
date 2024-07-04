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

    public static function checkParams(UrlCheck $url)
    {
        $document = new Document($url->getHtmlBody());
        $h1 = optional($document->first('h1'))->text();
        $title = optional($document->first('title'))->text();
        $description = optional($document->find('meta[name=description]')[0])->content;
        $url->setH1($h1);
        $url->setTitle($title);
        $url->setDescription($description);
    }
}
