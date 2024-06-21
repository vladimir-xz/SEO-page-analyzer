<?php

namespace Hexlet\Code;

use Illuminate\Support\Str;
use Hexlet\Code\Database\ConnectUrl;
use Hexlet\Code\Database\InsertUrl;

class DbHandler
{
    private string $dbName;
    private \PDO $db;

    public function __construct(
        string $databaseName
    ) {
        try {
            $this->dbName = ucfirst($databaseName) . 'Database';
            $DbClass = 'Hexlet\\Code\\' . $this->dbName . '\\Connect';
            $database = $DbClass::get()->connect();
            $this->db = $database;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function process(
        string $action,
        mixed $value = null
    ) {
        try {
            $properName = Str::of($action)->camel()->ucfirst();
            $actionClass = 'Hexlet\\Code\\' . $this->dbName . '\\' . $properName;
            return $actionClass::process($this->db, $value);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
