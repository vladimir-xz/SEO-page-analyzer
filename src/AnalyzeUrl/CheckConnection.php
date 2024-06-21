<?php

namespace Hexlet\Code\AnalyzeUrl;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Hexlet\Code\UrlCheck;

class CheckConnection
{
    public static function process(UrlCheck $url)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url->getName(), ['connect_timeout' => 3.14]);
            $status = $res->getStatusCode();
            $url->setHtmlBody($res->getBody()->__toString());
        } catch (TransferException $e) {
            throw new \Exception($e->getMessage());
        }
        $url->setStatusCode($status);
    }
}
