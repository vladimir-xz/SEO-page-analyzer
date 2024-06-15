<?php

namespace Hexlet\Code;

class Engine
{
    private $processor;
    private $value;

    public function __construct(
        $databaseName,
        $action,
        $value = null
    ) {
        $connectData = "Connect{$databaseName}";
        $actionName = "{$action}{$databaseName}";
        $database = $connectData::get()->connect();
        $processor = new $actionName($database);
        $this->processor = $processor;
        $this->value = $value;
    }

    public function process()
    {
        return $this->processor->process($this->value);
    }
}
