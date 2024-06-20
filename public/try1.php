<?php

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Hexlet\Code\UrlCheckRecord;
use DiDom\Document;

$curl_handle = curl_init();
curl_setopt($curl_handle, CURLOPT_URL, 'https://seznam.cz');
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'SEO analyzer');
$query = curl_exec($curl_handle);
curl_close($curl_handle);

var_dump($query);
