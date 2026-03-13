<?php
class Database
{
    # Fremtidig så skal verdiene her settes når objektet initsialiseres..
    private PDO $db;
    private string $_db_host = "db";
    private string $_db_name = "datasikkerhet";
    private string $_db_user = "root";
    private string $_db_passwd = "dev";

    public bool $connection_status;

    public function __construct(string $host, string $db_name, string $db_user, string $db_password)
    {
        try {
            $this->db = new PDO("mysql:host=$this->_db_host;dbname=$this->_db_name", $this->_db_user, $this->_db_passwd);
            $this->connection_status = true;
        } catch (PDOException $e) {
            $this->connection_status = false;
            throw($e);
        }
    }

    public function getDb(): PDO
    {
        return $this->db;
    }
}
