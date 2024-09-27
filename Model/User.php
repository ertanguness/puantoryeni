<?php

require_once "BaseModel.php";

class User extends Model
{
    protected $table = 'users';
    public function __construct()
    {
        parent::__construct($this->table);
    }


    
    public function allByFirms($firm_id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE firm_id = :firm_id");
        $sql->execute(['firm_id' => $firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
    public function getUserByEmailandPassword($email, $password)
    {
        $sql = $this->db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $sql->execute(array($email, $password));
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function getUser($id)
    {
        $sql = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $sql->execute(array($id));
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function getUserByEmail($email)
    {
        $sql = $this->db->prepare("SELECT * FROM users WHERE email = ? ");
        $sql->execute(array($email));
        return $sql->fetch(PDO::FETCH_OBJ);
    }

 

    public function saveUser($data)
    {
        $this->attributes = $data;
        $this->isNew = true;
        if (isset($data["id"]) && $data["id"] > 0) {
            $this->isNew = false;
        }
        return parent::save();
    }

    public function roleName($id)
    {
        $sql = $this->db->prepare("SELECT * FROM userroles WHERE id = ?");
        $sql->execute(array($id));
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result->roleName ?? "Bilinmiyor";
    }


    public function authorities(){
        $sql = $this->db->prepare("SELECT * FROM authority");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}
