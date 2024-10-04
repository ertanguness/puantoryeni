<?php


require_once "BaseModel.php";

class Auths extends Model
{
    protected $table = "auths";
    public function __construct()
    {
        parent::__construct($this->table);
    }

    
    public function auths(){
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE parent_id = ? and is_active = 1");
        $sql->execute([0]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //alt yetkiler getirilir
    public function subAuths($id){
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE parent_id = ? and is_active = 1");
        $sql->execute([$id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}
