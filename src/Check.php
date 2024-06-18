<?php

namespace Hexlet\Code;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class Check
{
    public static function process(string $url)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url);
            $status = $res->getStatusCode();
            return $status;
        } catch (TransferException $e) {
            return $e->getMessage();
        }
    }
}
