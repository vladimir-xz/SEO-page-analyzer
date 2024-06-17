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
        $this->dbName = $databaseName;
        $DbClass = 'Hexlet\\Code\\' . ucfirst($databaseName) . '\\Connect';
        $database = $DbClass::get()->connect();
        $this->db = $database;
    }

    public function process(
        $action,
        $value = null
    ) {
        $actionClass = 'Hexlet\\Code\\' . ucfirst($this->dbName) . '\\' . ucfirst($action);
        return $actionClass::process($this->db, $value);
    }
}
