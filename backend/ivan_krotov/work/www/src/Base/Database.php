<?php

namespace root\Base;

use PDO;

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection;

    private string $host = "mysql";
    private string $username = "root";
    private string $password = "secret";

    public function __construct()
    {
        $this->createConnection();
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    public static function getInstance(): self {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function createConnection()
    {
        $this->connection = new PDO("mysql:host=$this->host;port=3306;dbname=app", $this->username, $this->password);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    public function getColumns(string $tableName)
    {
        $q = $this->connection->prepare("DESCRIBE $tableName");
        $q->execute();
        return $q->fetchAll(PDO::FETCH_COLUMN);
    }
}