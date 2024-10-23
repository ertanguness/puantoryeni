<?php

require_once "BaseModel.php";



class Cases extends Model
{
    protected $table  = "cases";
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function allCaseWithUserId($user_id)
    {
        $query = $this->db->prepare("SELECT * FROM $this->table WHERE account_id = :user_id");
        $query->execute(array("user_id" => $user_id));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function allCaseWithFirmId()
    {
        $firm_id = $_SESSION['firm_id'];
        $query = $this->db->prepare("SELECT * FROM $this->table WHERE firm_id = ?");
        $query->execute([$firm_id]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function setDefaultCase($id)
    {
        $query = $this->db->prepare("UPDATE $this->table SET isDefault = 0");
        $query->execute();
        $query = $this->db->prepare("UPDATE $this->table SET isDefault = 1 WHERE id = ?");
        $query->execute([$id]);
        return $query->rowCount();
    }

    public function getDefaultCaseIdByFirm()
    {
        $query = $this->db->prepare("SELECT id FROM $this->table WHERE firm_id = ? and isDefault = 1");
        $query->execute([$_SESSION['firm_id']]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->id ?? 0;
    }

    public function countCaseByFirm(){
        $query = $this->db->prepare("SELECT count(id) as count FROM $this->table WHERE firm_id = ?");
        $query->execute([$_SESSION['firm_id']]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->count;
    }


   
}
