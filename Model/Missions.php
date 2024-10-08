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
    
    public function getHeaderFromMissionsFirm($firm_id)
    {
        $sql =  $this->db->prepare("SELECT m.header_id, mh.header_order
                                            FROM $this->table m
                                            LEFT JOIN mission_headers mh ON mh.id = m.header_id
                                            WHERE m.firm_id = ?
                                            GROUP BY m.header_id, mh.header_order
                                            ORDER BY mh.header_order;");
        $sql->execute([$firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function getMissionsByHeader($header_id)
    {
        $sql =  $this->db->prepare("SELECT * FROM $this->table WHERE header_id = ?");
        $sql->execute([$header_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

}