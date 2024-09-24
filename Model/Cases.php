<?php

require_once "BaseModel.php";



class Cases extends Model
{
    protected $table  = "cases";
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function allWithUserId($user_id)
    {
        $query = $this->db->prepare("SELECT * FROM cases WHERE account_id = :user_id");
        $query->execute(array("user_id" => $user_id));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function allWithFirmId($firm_id)
    {
        $query = $this->db->prepare("SELECT * FROM cases WHERE firm_id = ?");
        $query->execute([$firm_id]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

   
}
