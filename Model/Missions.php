<?php


require_once "BaseModel.php";

class Missions extends Model
{
    protected $table = "missions";
    protected $table_process = "mission_process";
    public function __construct()
    {
        parent::__construct($this->table);
    }

   
    public function getMissionsFirm($firm_id)
    {
        $sql =  $this->db->prepare("SELECT * FROM $this->table WHERE firm_id = ?");
        $sql->execute([$firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

   

}