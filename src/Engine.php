<?php

namespace Hexlet\Code;

class Engine
{
    private $action;
    private $processor;
    private $value;

    public function __construct($databaseName, $action, $value)
    {
        $connectData = "Connect{$databaseName}";
        $actionName = "{$action}{$databaseName}";
        $database = $connectData::get()->connect();
        $processor = new $actionName($database);
        $this->processor = $processor;
        $this->action = $action;
        $this->value = $value;
    }

    public function process()
    {
        $action = $this->action;
        return $this->processor->$action($this->value);
    }
}
