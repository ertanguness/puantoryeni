<?php

namespace Database;

use PDO;

class Db
{
    protected $db;
    protected $dbname = "mbeyazil_puantoryeni";
    protected $host = "localhost";

    protected $username = "root";


    public function __construct()
    {
        // $this->db = new PDO("mysql:host=localhost;dbname=mbeyazil_puantoryeni", "mbeyazil_root", "{bEb1}l#S*m5");
        $this->db = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, "");
    }

    // $db özelliğine dışarıdan erişim sağlayan metod
    public function connect()
    {
        return $this->db;
    }

    public function disconnect()
    {
        $this->db = null;
    }


    // Transaction başlatma
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    // Transaction commit etme
    public function commit()
    {
        return $this->db->commit();
    }

    // Transaction rollback etme
    public function rollBack()
    {
        return $this->db->rollBack();
    }
}