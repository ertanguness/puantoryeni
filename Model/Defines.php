<?php


require_once "BaseModel.php";

class Defines extends Model
{
    protected $table = "defines";
    public function __construct()
    {
        parent::__construct($this->table);
    }

    //servis konusu tanım : 1
    public function getServiceHeads()
    {
        $sql =  $this->db->prepare("SELECT * FROM $this->table WHERE user_id = ? and statu = ?");
        $sql->execute([1]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }


    //gelir-gider türü tanım : 2
    public function getIncExpTypesByFirm($firm_id)
    {
        $sql =  $this->db->prepare("SELECT * FROM $this->table WHERE firm_id = ? and `group_type` = ?");
        $sql->execute([$firm_id, 2]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}