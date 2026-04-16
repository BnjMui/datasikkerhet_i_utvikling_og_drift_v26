<?php

namespace DatasikkerhetG7\Repository;

require __DIR__ . "/../../vendor/autoload.php";

use PDO;
use PDOException;
use DatasikkerhetG7\Logger\DG7Logger;

class Database
{
    private PDO $db;

    public bool $connection_status;

    public function __construct(string $host, string $db_name, string $db_user, string $db_password)
    {
        $logger = new DG7Logger("Database_connection");
        $log = $logger->getLogger();
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
            $this->connection_status = true;

            $log->info("Database connection made");
        } catch (PDOException $e) {
            $log->error($e->getMessage(), ["error_code" => $e->getCode()]);

            $this->connection_status = false;
        }
    }

    public function getDb(): PDO | false
    {
        if (!$this->connection_status) {
            return false;
        }

        return $this->db;
    }
}
