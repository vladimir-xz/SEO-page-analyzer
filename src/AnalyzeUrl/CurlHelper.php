<?php

namespace Hexlet\Code\AnalyzeUrl;

class CurlHelper
{
    public static function getHtml(string $url)
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'SEO analyzer');
        $html = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $html;
    }
}
