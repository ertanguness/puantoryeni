<?php


require_once "BaseModel.php";

class Roles extends Model
{
    protected $table = "userroles";
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getRolesByFirm($firm_id){
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE firm_id = ?");
        $sql->execute([$firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}
