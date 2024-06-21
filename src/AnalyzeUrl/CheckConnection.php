<?php

namespace Hexlet\Code\AnalyzeUrl;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Hexlet\Code\UrlCheckRecord;

class CheckConnection
{
    public static function process(UrlCheckRecord $url)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url->getName());
            $status = $res->getStatusCode();
            $url->setHtmlBody($res->getBody()->__toString());
        } catch (TransferException $e) {
            throw new \Exception($e->getMessage());
        }
        $url->setStatusCode($status);
    }
}
