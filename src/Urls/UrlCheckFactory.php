<?php

namespace Hexlet\Code\Urls;

class UrlCheckFactory
{
    public function create(\stdClass $urlRecord)
    {
        return new UrlCheck($urlRecord);
    }
}
