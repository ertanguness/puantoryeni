<?php

namespace Database;

use PDO;

class Db {
    protected $db;

    public function __construct() {
        $this->db = new PDO("mysql:host=localhost;dbname=puantor", "root", "");
    }

     // $db özelliğine dışarıdan erişim sağlayan metod
     public function connect() {
        return $this->db;
    }

    public function disconnect() {
        $this->db = null;
    }
}