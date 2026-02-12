<?php

class Database
{
    private ?PDO $db = null;

    private string $_db_host = "127.0.0.1";
    private string $_db_name = "datasikkerhet";
    private string $_db_user = "root";
    private string $_db_passwd = "dev";


    public bool $connection_status = false;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->_db_host};dbname={$this->_db_name};charset=utf8";

            $this->db = new PDO($dsn, $this->_db_user, $this->_db_passwd);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection_status = true;

        } catch (PDOException $e) { echo json_encode([ "success" => false, "error" => $e->getMessage() ]); exit; }
    }

    public function getDb(): ?PDO
    {
        return $this->db;
    }
}
