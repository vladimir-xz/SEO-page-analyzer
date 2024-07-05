<?php

namespace Hexlet\Code\Urls;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use DiDom\Document;
use Hexlet\Code\Urls\UrlCheck;

class Analyze
{
    private \Hexlet\Code\Urls\UrlCheckFactory $urlCheckFactory;

    public function __construct(\Hexlet\Code\Urls\UrlCheckFactory $urlCheckFactory)
    {
        $this->urlCheckFactory = $urlCheckFactory;
    }

    public function checkUrl(\stdClass $url): UrlCheck
    {
        $urlCheck = $this->urlCheckFactory->create($url);
        $this->getStatusAndBody($urlCheck);
        $this->getParams($urlCheck);
        return $urlCheck;
    }

    private function getStatusAndBody(UrlCheck $url): void
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

    private function getParams(UrlCheck $url): void
    {
        $document = new Document($url->getHtmlBody());
        $h1 = $document->first('h1')?->text();
        $title = $document->first('title')?->text();
        $description = $document->first('meta[name=description]')?->getAttribute('content');
        $url->setH1($h1);
        $url->setTitle($title);
        $url->setDescription($description);
    }
}
