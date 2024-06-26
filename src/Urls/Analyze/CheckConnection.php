<?php

namespace Hexlet\Code\Urls\Analyze;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Hexlet\Code\Urls\UrlCheck;

class CheckConnection
{
    public static function process(UrlCheck $url)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url->getName(), ['connect_timeout' => 3.14, 'http_errors' => false]);
            $status = $res->getStatusCode();
            $url->setHtmlBody($res->getBody()->__toString());
            $url->setStatusCode($status);
        } catch (TransferException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
