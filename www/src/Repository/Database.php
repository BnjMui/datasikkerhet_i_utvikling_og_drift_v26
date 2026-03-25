<?php

namespace DatasikkerhetG7\Repository;

use PDO;
use PDOException;

class Database
{
    # Fremtidig så skal verdiene her settes når objektet initsialiseres..
    private PDO $db;
    # private string $_db_host = "db";
    # private string $_db_name = "datasikkerhet";
    # private string $_db_user = "root";
    # private string $_db_passwd = "dev";

    public bool $connection_status;

    public function __construct(string $host, string $db_name, string $db_user, string $db_password)
    {
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
            $this->connection_status = true;
        } catch (PDOException $e) {
            # TODO:
            # Logg feil med monolog...
            $this->connection_status = false;
            echo $e->getMessage();
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
