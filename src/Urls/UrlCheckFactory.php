<?php

namespace Hexlet\Code\Urls;

class UrlCheckFactory
{
    public function create(\stdClass $urlRecord): UrlCheck
    {
        return new UrlCheck($urlRecord);
    }
}
