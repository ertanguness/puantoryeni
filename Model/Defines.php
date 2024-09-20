<?php


require_once "BaseModel.php";

class Defines extends Model
{
    protected $table = "defines";
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getServiceHeads()
    {
        $sql =  $this->db->prepare("SELECT * FROM defines WHERE statu = ?");
        $sql->execute([1]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}