<?php

namespace Hexlet\Code;

use Hexlet\Code\Database\ConnectUrl;
use Hexlet\Code\Database\InsertUrl;

class DbHandler
{
    private $dbName;
    private $db;

    public function __construct(
        $databaseName
    ) {
        $this->dbName = ucfirst($databaseName);
        $DbClass = 'Hexlet\\Code\\' . $this->dbName . '\\Connect';
        $database = $DbClass::get()->connect();
        $this->db = $database;
    }

    public function process(
        $action,
        $value = null
    ) {
        // $properName = \Funct\Strings\camelize();
        $properName = str_replace(' ', '', ucwords($action));
        $actionClass = 'Hexlet\\Code\\' . $this->dbName . '\\' . $properName;
        return $actionClass::process($this->db, $value);
    }
}
