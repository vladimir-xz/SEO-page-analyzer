<?php

namespace Hexlet\Code\AnalyzeUrl;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Hexlet\Code\UrlCheckRecord;

class CheckConnection
{
    public static function process(UrlCheckRecord $url)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url->name);
            $status = $res->getStatusCode();
        } catch (TransferException $e) {
            throw new \Exception($e->getMessage());
        }
        $url->setStatusCode($status);
    }
}
