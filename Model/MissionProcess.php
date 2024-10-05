<?php


require_once "BaseModel.php";

class MissionProcess extends Model
{
    protected $table = "mission_process";
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getMissionProcessFirm($firm_id)
    {
        $sql =  $this->db->prepare("SELECT * FROM $this->table WHERE firm_id = ?  order by process_order asc");
        $sql->execute([$firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}