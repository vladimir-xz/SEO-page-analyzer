<?php

namespace Hexlet\Code;

use Hexlet\Code\Database\ConnectUrl;
use Hexlet\Code\Database\InsertUrl;

class Engine
{
    private $processor;
    private $value;

    public function __construct(
        $databaseName,
        $action,
        $value = null
    ) {
        // $connectData = "Connect{$databaseName}";
        // $actionName = "{$action}{$databaseName}";
        $DbClass = 'Hexlet\\Code\\' . ucfirst($databaseName) . '\\Connect';
        $actionClass = 'Hexlet\\Code\\' . ucfirst($databaseName) . '\\' . ucfirst($action);
        $database = $DbClass::get()->connect();
        $processor = new $actionClass($database);
        $this->processor = $processor;
        $this->value = $value;
    }

    public function process()
    {
        return $this->processor->process($this->value);
    }
}
