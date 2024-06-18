<?php

namespace Hexlet\Code;

class UrlRecord
{
    private $id;
    private $name;
    private $createdAt;

    public function __construct(array $record)
    {
        $this->id = $record['id'];
        $this->name = $record['name'];
        $this->createdAt = $record['created_at'];
    }

    public function __get($key)
    {
        return $this->$key;
    }
}
